<?php

namespace App\Jobs;

use App\Models\LevelMemberModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CalcMemberExpenseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $mode; // mode =0 all, mode=1 syncfromaccurate, mode=2 synctoaccurate
    public $timeout = 3600;

    /**
     * Create a new job instance.
     */
    public function __construct($mode = 0)
    {
        $this->mode = $mode;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Mail::raw('Test email from Laravel', function ($message) {
        //     $message->to('mochamad.mmz@bsi.ac.id')->subject('Test Email');
        // });
        if ($this->mode == 0) {
            $this->refreshAll();
        } else {
            // $this->refreshDaily();
            $this->refreshAll();
        }
    }


    public function refreshAll(): void
    {
        Log::info('CalcMemberExpenseJob executed at: ' . now());

        // Ambil tahun pertama dari tabel transactions
        $userFirstTransactionYears = DB::table('transactions')
            ->whereNotNull('tgl_invoice')
            // ->where('member_user_id', '10339')
            ->selectRaw("member_user_id COLLATE utf8mb4_general_ci as member_user_id, MIN(YEAR(tgl_invoice)) as first_year")
            ->groupBy('member_user_id');

        // Ambil tahun pertama dari tabel fee_number
        $userFirstFeeYears = DB::table('fee_number')
            ->whereNotNull('tgl_periode') // Pastikan tidak NULL
            ->where('tgl_periode', '!=', '0000-00-00') // Hindari tanggal tidak valid
            ->selectRaw("member_user_id COLLATE utf8mb4_general_ci as member_user_id, MIN(YEAR(tgl_periode)) as first_year")
            ->groupBy('member_user_id');

        // Gabungkan kedua sumber data
        $userFirstYears = DB::table(DB::raw("({$userFirstTransactionYears->toSql()} UNION {$userFirstFeeYears->toSql()}) as combined"))
            ->mergeBindings($userFirstTransactionYears)
            ->mergeBindings($userFirstFeeYears)
            ->selectRaw('member_user_id, MIN(first_year) as first_year')
            ->groupBy('member_user_id')
            ->pluck('first_year', 'member_user_id');

        $currentYear = date('Y');

        // Ambil semua pengguna dari tabel users
        $allUserIds = DB::table('users')
            // ->where('id', '10339')
            ->pluck('id');

        foreach ($allUserIds as $userId) {
            if (!isset($userFirstYears[$userId])) {
                // Jika user tidak memiliki transaksi, atur points menjadi 0
                DB::table('users')
                    ->where('id', $userId)
                    ->update([
                        'points' => 0,
                        'level_member_id' => null, // Reset level jika tidak ada transaksi
                    ]);

                // Log::info("User_id: $userId has no transactions. Points set to 0.");
                continue; // Lanjutkan ke user berikutnya
            }
            $firstYear = $userFirstYears[$userId];
            $yearsToProcess = range($firstYear, $currentYear);
            $lastLevel = null;
            $currentLevelId = null; // Menyimpan level tahun berjalan

            foreach ($yearsToProcess as $year) {
                Log::info("Processing user_id: $userId for year: $year");

                // Hitung total_spent dari transaksi
                $totalSpentFromTransactions = DB::table('detail_transactions as dt')
                    ->join('transactions as t', DB::raw('t.id COLLATE utf8mb4_general_ci'), '=', DB::raw('dt.transaction_id COLLATE utf8mb4_general_ci'))
                    ->leftJoin('detail_retur_penjualan as dr', function ($join) {
                        $join->on(DB::raw('dr.retur_no COLLATE utf8mb4_general_ci'), '=', DB::raw('dt.retur_no COLLATE utf8mb4_general_ci'))
                            ->on(DB::raw('dr.product_id COLLATE utf8mb4_general_ci'), '=', DB::raw('dt.product_id COLLATE utf8mb4_general_ci'));
                    })
                    ->whereRaw('t.member_user_id COLLATE utf8mb4_general_ci = ?', $userId)
                    ->whereYear('t.tgl_invoice', $year)
                    ->selectRaw('SUM(
                    CASE 
                        WHEN COALESCE(dt.dpp_amount, 0) = 0 THEN
                            COALESCE(dt.total_price, 0)
                        ELSE
                            COALESCE(dt.dpp_amount, 0)
                    END -   
                    CASE 
                        WHEN COALESCE(dr.return_amount, 0) = 0 AND COALESCE(dt.retur_qty, 0) > 0 THEN
                            (COALESCE(dt.dpp_amount, 0) / COALESCE(dt.qty, 1)) * COALESCE(dt.retur_qty, 0)
                        ELSE 
                            COALESCE(dr.return_amount, 0)
                    END
                ) as total_spent')
                    ->value('total_spent') ?? 0;

                $totalSpentFromTransactions = round($totalSpentFromTransactions);

                // Hitung total_spent dari fee_number
                $totalSpentFromFee = DB::table('fee_number')
                    ->where('member_user_id', $userId)
                    ->where('tgl_periode', '!=', '0000-00-00') // Hindari tanggal tidak valid
                    ->whereYear('tgl_periode', $year) // Hanya tahun yang sedang diproses
                    ->sum('dpp_penjualan') ?? 0;

                $totalSpentFromFee = round($totalSpentFromFee);

                // Gabungkan total dari transaksi dan fee_number
                $totalSpent = $totalSpentFromTransactions + $totalSpentFromFee;

                // Jika tidak ada transaksi tapi ada fee, tetap proses fee
                if ($totalSpentFromTransactions == 0 && $totalSpentFromFee > 0) {
                    $totalSpent = $totalSpentFromFee;
                }

                Log::info("Total spent (transactions + fee) for user_id: $userId in year: $year is $totalSpent");

                // Gunakan logika menentukan level seperti sebelumnya
                $previousYearLevel = DB::table('member_spent')
                    ->where('user_id', $userId)
                    ->where('tahun', $year - 1)
                    ->first();

                $levels = LevelMemberModel::where('publish', 1)->orderBy('level', 'desc')->get();
                $lastLevel = $levels->firstWhere('id', $previousYearLevel->level ?? null);
                $levelId = null;

                if ($totalSpent > 0) {
                    if ($previousYearLevel && $totalSpent < $previousYearLevel->total_spent) {
                        $nextLevel = $levels->firstWhere('level', $lastLevel->level + 1);
                        $levelId = $nextLevel ? $nextLevel->id : $lastLevel->id;
                        $lastLevel = $nextLevel ? $nextLevel : $lastLevel;
                    } else {
                        foreach ($levels as $level) {
                            if ($totalSpent <= $level->limit_transaction) {
                                $levelId = $level->id;
                                $lastLevel = $level;
                                break;
                            }
                        }
                        if ($totalSpent > $levels->last()->limit_transaction) {
                            $levelId = $levels->last()->id;
                            $lastLevel = $levels->last();
                        }
                    }
                } else {
                    if ($lastLevel) {
                        $nextLevel = $levels->firstWhere('level', $lastLevel->level + 1);
                        $levelId = $nextLevel ? $nextLevel->id : $lastLevel->id;
                        $lastLevel = $nextLevel ? $nextLevel : $lastLevel;
                    } else {
                        $levelId = $levels->last()->id ?? null;
                        $lastLevel = $levels->last();
                    }
                }

                if ($year == $currentYear && $previousYearLevel) {
                    $levelId = $previousYearLevel->level;
                    $lastLevel = $levels->firstWhere('id', $levelId);
                }

                DB::table('member_spent')->updateOrInsert(
                    [
                        'user_id' => $userId,
                        'tahun' => $year,
                    ],
                    [
                        'total_spent' => $totalSpent,
                        'level' => $levelId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
                // Simpan level tahun berjalan ke variabel
                if ($year == $currentYear) {
                    if ($totalSpent <= ($previousYearLevel->total_spent ?? 0)) {
                        $currentLevelId = $previousYearLevel->level; // Tetap gunakan level tahun sebelumnya
                    } else {
                        $currentLevelId = $levelId; // Gunakan level yang sesuai dengan totalSpent
                    }
                }
            }

            // Perbarui level_member_id dan points di tabel users untuk tahun berjalan
            if ($currentLevelId) {
                // Hitung total poin dari member_spent
                $totalPoints = DB::table('member_spent')
                    ->where('user_id', $userId)
                    ->where('tahun', $currentYear)
                    ->sum('total_spent') / 1000;
                // Hitung total poin dari rewards
                $totalRewardsPoints = DB::table('member_rewards')
                    ->where('user_id', $userId)
                    ->where('status', '<>', '3') // Menggunakan operator '<>' untuk tidak sama dengan
                    ->sum('point');

                // Hitung poin akhir
                $finalPoints = round($totalPoints) - $totalRewardsPoints;

                // Log::info("Updating user_id: $userId with final points: $finalPoints");

                // Update tabel users
                DB::table('users')
                    ->where('id', $userId)
                    ->update([
                        'level_member_id' => $currentLevelId,
                        'points' => $finalPoints,
                    ]);
            }
        }

        Log::info('CalcMemberExpenseJob completed.');
    }




    public function refreshDaily(): void
    {
        // Log::info('Daily refresh executed at: ' . now());

        $currentYear = date('Y');

        // Ambil semua pengguna dengan transaksi di tahun berjalan
        $usersWithCurrentYearTransactions = DB::table('transactions')
            ->whereNotNull('tgl_invoice')
            ->whereYear('tgl_invoice', $currentYear)
            ->select('member_user_id')
            ->distinct()
            ->pluck('member_user_id');

        // Ambil level yang dipublish
        $levels = LevelMemberModel::where('publish', 1)->orderBy('level', 'desc')->get();

        foreach ($usersWithCurrentYearTransactions as $userId) {
            // Log::info("Processing user_id: $userId for year: $currentYear");

            // Hitung total_spent untuk tahun berjalan
            $totalSpent = DB::table('detail_transactions as dt')
                ->join('transactions as t', 't.id', '=', 'dt.transaction_id')
                ->leftJoin('detail_retur_penjualan as dr', function ($join) {
                    $join->on(DB::raw('dr.retur_no COLLATE utf8mb4_unicode_ci'), '=', DB::raw('dt.retur_no COLLATE utf8mb4_unicode_ci'))
                        ->on(DB::raw('dr.product_id COLLATE utf8mb4_unicode_ci'), '=', DB::raw('dt.product_id COLLATE utf8mb4_unicode_ci'));
                })
                ->where('t.member_user_id', $userId)
                ->whereYear('t.tgl_invoice', $currentYear)
                ->selectRaw('SUM(
                    COALESCE(dt.dpp_amount, 0) - 
                    CASE 
                        WHEN COALESCE(dr.return_amount, 0) = 0 AND COALESCE(dt.retur_qty, 0) > 0 THEN
                            (COALESCE(dt.dpp_amount, 0) / COALESCE(dt.qty, 1)) * COALESCE(dt.retur_qty, 0)
                        ELSE 
                            COALESCE(dr.return_amount, 0)
                    END
                ) as total_spent')
                ->value('total_spent') ?? 0;

            $totalSpent = round($totalSpent); // Pembulatan total_spent

            // Log::info("Total spent for user_id: $userId in year: $currentYear is $totalSpent");

            // Tentukan level berdasarkan total_spent
            $levelId = null;
            $lastLevel = DB::table('member_spent')
                ->where('user_id', $userId)
                ->where('tahun', '<', $currentYear)
                ->orderBy('tahun', 'desc')
                ->value('level');

            if ($totalSpent > 0) {
                foreach ($levels as $level) {
                    if ($totalSpent <= $level->limit_transaction) {
                        $levelId = $level->id;
                        break;
                    }
                }
            } else {
                if ($lastLevel) {
                    $lastLevelModel = $levels->firstWhere('id', $lastLevel);
                    if ($lastLevelModel) {
                        $nextLevel = $levels->firstWhere('level', $lastLevelModel->level + 1);
                        $levelId = $nextLevel ? $nextLevel->id : $lastLevelModel->id;
                    }
                } else {
                    $levelId = $levels->last()->id; // Level terendah
                }
            }

            // Simpan ke tabel member_spent
            DB::table('member_spent')->updateOrInsert(
                [
                    'user_id' => $userId,
                    'tahun' => $currentYear,
                ],
                [
                    'total_spent' => $totalSpent,
                    'level' => $levelId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            // Hitung total poin dari member_spent
            $totalPoints = DB::table('member_spent')
                ->where('user_id', $userId)
                ->where('tahun', $currentYear)
                ->sum('total_spent') / 1000;

            // Hitung total poin dari rewards
            $totalRewardsPoints = DB::table('member_rewards as mr')
                ->join('rewards as r', 'mr.reward_id', '=', 'r.id')
                ->where('mr.user_id', $userId)
                ->sum('r.point');

            // Hitung poin akhir
            $finalPoints = round($totalPoints) - $totalRewardsPoints;

            // Log::info("User ID: $userId - Total Points: " . round($totalPoints) . ", Total Rewards Points: $totalRewardsPoints, Final Points: $finalPoints");

            // Update tabel users
            DB::table('users')
                ->where('id', $userId)
                ->update([
                    'level_member_id' => $levelId,
                    'points' => $finalPoints,
                ]);
        }

        // Log::info('Daily refresh completed.');
    }
}
