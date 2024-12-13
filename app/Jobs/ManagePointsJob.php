<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use App\Models\CatatanPrivateModel;

class ManagePointsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $action;

    /**
     * Create a new job instance.
     *
     * @param string $action The action to perform: "reset" or "update"
     */
    public function __construct($action)
    {
        $this->action = $action;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->action === 'reset') {
            $this->resetPoints();
        } elseif ($this->action === 'update') {
            $this->updatePointsIfNeeded();
        }
    }

    /**
     * Reset all points by truncating the member_points table and recalculating points.
     */
    private function resetPoints(): void
    {
        // Truncate the member_points table
        DB::table('member_points')->truncate();

        // Insert recalculated points into member_points
        DB::insert("
        INSERT INTO member_points (user_id, transaction_id, product_id, points, notes, received_at, created_at)
        SELECT 
            u.id AS user_id,
            t.id AS transaction_id,
            dt.product_id AS product_id,
            ROUND((
                COALESCE(dt.dpp_amount, 0) - 
                CASE 
                    WHEN COALESCE(dr.return_amount, 0) = 0 AND COALESCE(dt.retur_qty, 0) > 0 THEN
                        (COALESCE(dt.dpp_amount, 0) / COALESCE(dt.qty, 1)) * COALESCE(dt.retur_qty, 0)
                    ELSE 
                        COALESCE(dr.return_amount, 0)
                END
            ) / 1000) AS points,
            'Poin dari transaksi' AS notes,
            NOW() AS received_at,
            NOW() AS created_at
        FROM 
            users u
        JOIN 
            transactions t ON u.id = t.member_user_id COLLATE utf8mb4_unicode_ci
        JOIN 
            detail_transactions dt ON t.id = dt.transaction_id COLLATE utf8mb4_unicode_ci
        LEFT JOIN 
            detail_retur_penjualan dr ON dr.so_number = t.nomor_so COLLATE utf8mb4_unicode_ci
            AND dr.product_id = dt.product_id COLLATE utf8mb4_unicode_ci
    ");


        // Truncate user_points table
        DB::table('user_points')->truncate();

        // Insert recalculated total points into user_points
        DB::insert("
            INSERT INTO user_points (user_id, total_point, created_at, updated_at)
            SELECT 
                mp.user_id,
                ROUND(SUM(mp.points)) AS total_point,
                NOW() AS created_at,
                NOW() AS updated_at
            FROM 
                member_points mp
            GROUP BY 
                mp.user_id
        ");

        // Update the users table with total points
        DB::update("
            UPDATE users u
            JOIN (
                SELECT 
                    up.user_id, 
                    SUM(up.total_point) AS total_points, -- Total poin dari transaksi
                    COALESCE(SUM(r.point), 0) AS total_rewards_points -- Total poin dari rewards
                FROM 
                    user_points up
                LEFT JOIN 
                    member_rewards mr ON up.user_id = mr.user_id
                LEFT JOIN 
                    rewards r ON mr.reward_id = r.id
                GROUP BY 
                    up.user_id
            ) AS points_summary ON u.id = points_summary.user_id
            SET 
                u.points = points_summary.total_points - points_summary.total_rewards_points
        ");

        // Log the action
        CatatanPrivateModel::query()->insert([
            'catatan' => 'Reset points and updated user points',
            'created_at' => Carbon::now(),
        ]);
    }

    /**
     * Update points if there are changes in transactions or returns within the last 7 days.
     */
    private function updatePointsIfNeeded(): void
    {
        $sevenDaysAgo = Carbon::now()->subDays(7);

        // Ambil transaksi dan detail yang berubah dalam 7 hari terakhir
        $updatedTransactionIds = DB::table('transactions')
            ->join('detail_transactions', 'transactions.id', '=', 'detail_transactions.transaction_id')
            ->where('detail_transactions.updated_at', '>=', $sevenDaysAgo)
            ->orWhere('transactions.updated_at', '>=', $sevenDaysAgo)
            ->pluck('transactions.id');

        if ($updatedTransactionIds->isNotEmpty()) {
            // Update or Insert ke member_points hanya untuk transaksi yang berubah
            foreach ($updatedTransactionIds as $transactionId) {
                DB::table('member_points')->updateOrInsert(
                    ['transaction_id' => $transactionId],
                    [
                        'user_id' => DB::table('transactions')->where('id', $transactionId)->value('member_user_id'),
                        'points' => DB::table('detail_transactions')
                            ->where('transaction_id', $transactionId)
                            ->selectRaw('ROUND((
                            COALESCE(dpp_amount, 0) - 
                            CASE 
                                WHEN COALESCE(return_amount, 0) = 0 AND COALESCE(retur_qty, 0) > 0 THEN
                                    (COALESCE(dpp_amount, 0) / COALESCE(qty, 1)) * COALESCE(retur_qty, 0)
                                ELSE 
                                    COALESCE(return_amount, 0)
                            END
                        ) / 1000, 0) as points')
                            ->value('points'),
                        'notes' => 'Updated points for recent transactions',
                        'received_at' => Carbon::now(),
                        'created_at' => Carbon::now(),
                    ]
                );
            }

            // Recalculate user points based on updated member_points
            DB::table('user_points')->updateOrInsert(
                ['user_id' => DB::table('member_points')->distinct()->pluck('user_id')],
                [
                    'total_point' => DB::table('member_points')
                        ->selectRaw('SUM(points)')
                        ->whereColumn('user_id', 'users.id')
                        ->value('points'),
                    'updated_at' => Carbon::now(),
                    'created_at' => Carbon::now(),
                ]
            );

            // Update user points in users table
            DB::update("
            UPDATE users u
            JOIN (
                SELECT 
                    up.user_id, 
                    SUM(up.total_point) - COALESCE(SUM(mr.point), 0) AS final_points
                FROM 
                    user_points up
                LEFT JOIN 
                    member_rewards mr ON up.user_id = mr.user_id
                GROUP BY 
                    up.user_id
            ) AS points_summary ON u.id = points_summary.user_id
            SET 
                u.points = points_summary.final_points
        ");

            // Log the action
            CatatanPrivateModel::query()->insert([
                'catatan' => 'Updated points for recent changes in transactions or returns',
                'created_at' => Carbon::now(),
            ]);
        }
    }
}
