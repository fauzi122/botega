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
        if ($this->mode == 0) {
            $this->refreshAll();
        } else {
            $this->refreshDaily();
        }
    }


    public function refreshAll(): void
    {
        Log::info('CalcMemberExpenseJob executed at: ' . now());

        // Ambil semua pengguna dengan tahun transaksi pertama
        $userFirstTransactionYears = DB::table('transactions')
            ->whereNotNull('tgl_invoice')
            // ->where('member_user_id', '4165')
            ->selectRaw('member_user_id, MIN(YEAR(tgl_invoice)) as first_year')
            ->groupBy('member_user_id')
            ->pluck('first_year', 'member_user_id');

        $currentYear = date('Y');

        foreach ($userFirstTransactionYears as $userId => $firstYear) {
            $yearsToProcess = range($firstYear, $currentYear);
            $lastLevel = null;
            $currentLevelId = null; // Menyimpan level tahun berjalan

            foreach ($yearsToProcess as $year) {
                Log::info("Processing user_id: $userId for year: $year");

                // Hitung total_spent untuk tahun tersebut
                $totalSpent = DB::table('detail_transactions as dt')
                    ->join('transactions as t', 't.id', '=', 'dt.transaction_id')
                    ->leftJoin('detail_retur_penjualan as dr', function ($join) {
                        $join->on(DB::raw('dr.retur_no COLLATE utf8mb4_unicode_ci'), '=', DB::raw('dt.retur_no COLLATE utf8mb4_unicode_ci'))
                            ->on(DB::raw('dr.product_id COLLATE utf8mb4_unicode_ci'), '=', DB::raw('dt.product_id COLLATE utf8mb4_unicode_ci'));
                    })
                    ->where('t.member_user_id', $userId)
                    ->whereYear('t.tgl_invoice', $year)
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

                Log::info("Total spent for user_id: $userId in year: $year is $totalSpent");
                $previousYearLevel = DB::table('member_spent')
                    ->where('user_id', $userId)
                    ->where('tahun', $year - 1)
                    ->first();

                // Ambil level yang dipublish
                $levels = LevelMemberModel::where('publish', 1)->orderBy('level', 'desc')->get();
                $lastLevel = $levels->firstWhere('id', $previousYearLevel->level ?? null);
                $levelId = null;
                if ($totalSpent > 0) {
                    if ($previousYearLevel && $totalSpent < $previousYearLevel->total_spent) {
                        // Jika totalSpent lebih rendah dari tahun sebelumnya, turunkan satu level
                        $nextLevel = $levels->firstWhere('level', $lastLevel->level + 1); // Turun satu tingkat
                        $levelId = $nextLevel ? $nextLevel->id : $lastLevel->id; // Tetap di level saat ini jika tidak ada level lebih rendah
                        $lastLevel = $nextLevel ? $nextLevel : $lastLevel;
                        // dump($year . '->' . $levelId);
                    } else {
                        // Tetapkan level berdasarkan totalSpent
                        foreach ($levels as $level) {
                            if ($totalSpent <= $level->limit_transaction) {
                                $levelId = $level->id;
                                $lastLevel = $level; // Perbarui lastLevel ke level saat ini
                                break;
                            }
                        }
                    }
                } else {
                    if ($lastLevel) {
                        $nextLevel = $levels->firstWhere('level', $lastLevel->level + 1); // Turun satu tingkat
                        $levelId = $nextLevel ? $nextLevel->id : $lastLevel->id; // Tetap di level saat ini jika tidak ada level lebih rendah
                        $lastLevel = $nextLevel ? $nextLevel : $lastLevel;
                    } else {
                        $levelId = $levels->last()->id; // Level terendah sebagai default
                        $lastLevel = $levels->last();
                    }
                }

                // die;
                // Simpan ke tabel member_spent
                DB::table('member_spent')->updateOrInsert(
                    [
                        'user_id' => $userId,
                        'tahun' => $year,
                    ],
                    [
                        'total_spent' => $totalSpent,
                        'level' => $levelId, // Simpan id level
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );

                // Simpan level tahun berjalan ke variabel
                if ($year == $currentYear) {
                    $currentLevelId = $levelId;
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
                $totalRewardsPoints = DB::table('member_rewards as mr')
                    ->join('rewards as r', 'mr.reward_id', '=', 'r.id')
                    ->where('mr.user_id', $userId)
                    ->sum('r.point');

                // Hitung poin akhir
                $finalPoints = round($totalPoints) - $totalRewardsPoints;

                Log::info("Updating user_id: $userId with final points: $finalPoints");

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
        Log::info('Daily refresh executed at: ' . now());

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
            Log::info("Processing user_id: $userId for year: $currentYear");

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

            Log::info("Total spent for user_id: $userId in year: $currentYear is $totalSpent");

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

            Log::info("User ID: $userId - Total Points: " . round($totalPoints) . ", Total Rewards Points: $totalRewardsPoints, Final Points: $finalPoints");

            // Update tabel users
            DB::table('users')
                ->where('id', $userId)
                ->update([
                    'level_member_id' => $levelId,
                    'points' => $finalPoints,
                ]);
        }

        Log::info('Daily refresh completed.');
    }
}
