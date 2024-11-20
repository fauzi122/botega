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

        // Recalculate points and insert into member_points
        DB::insert("
           INSERT INTO member_points (user_id, transaction_id, points, notes, received_at, created_at)
           SELECT 
               u.id AS user_id,
               t.id AS transaction_id,
               ROUND((COALESCE(dt.dpp_amount, 0) - (COALESCE(dt.retur_qty, 0) * COALESCE(dt.sale_price, 0))) / 1000) AS points,
               'Poin dari transaksi' AS notes,
               NOW() AS received_at,
               NOW() AS created_at
           FROM 
               users u
           JOIN 
               transactions t ON u.id = t.member_user_id
           JOIN 
               detail_transactions dt ON t.id = dt.transaction_id
        ");

        // Truncate user_points table to clear old data
        DB::table('user_points')->truncate();

        // Insert new calculated total points into user_points
        DB::insert("
            INSERT INTO user_points (user_id, total_point, created_at, updated_at)
            SELECT 
                mp.user_id,
                ROUND(SUM(mp.points)) AS total_point, -- Pastikan nilai dibulatkan
                NOW() AS created_at,
                NOW() AS updated_at
            FROM 
                member_points mp
            GROUP BY 
                mp.user_id
        ");

        // Update points in users table by deducting redeemed points from member_rewards
        DB::update("
            UPDATE users u
            JOIN (
                SELECT 
                    up.user_id, 
                    SUM(up.total_point) - COALESCE(SUM(CASE WHEN mr.status != 3 THEN mr.point ELSE 0 END), 0) AS final_points
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

        // Periksa apakah ada perubahan dalam 7 hari terakhir
        $hasChanges = DB::table('transactions')->where('updated_at', '>=', $sevenDaysAgo)->exists() ||
            DB::table('detail_transactions')->where('updated_at', '>=', $sevenDaysAgo)->exists();

        if ($hasChanges) {
            // Hitung ulang poin hanya untuk data yang diperbarui
            DB::insert("
            INSERT INTO member_points (user_id, transaction_id, points, notes, received_at, created_at)
            SELECT 
                u.id AS user_id,
                t.id AS transaction_id,
                ROUND((COALESCE(dt.dpp_amount, 0) - (COALESCE(dt.retur_qty, 0) * COALESCE(dt.sale_price, 0))) / 1000) AS points,
                'Poin dari transaksi' AS notes,
                NOW() AS received_at,
                NOW() AS created_at
            FROM 
                users u
            JOIN 
                transactions t ON u.id = t.member_user_id
            JOIN 
                detail_transactions dt ON t.id = dt.transaction_id
            WHERE t.updated_at >= ? OR dt.updated_at >= ?
        ", [$sevenDaysAgo, $sevenDaysAgo]);

            // Update total points in user_points table
            DB::update("
            INSERT INTO user_points (user_id, total_point, created_at, updated_at)
            SELECT 
                mp.user_id,
                ROUND(SUM(mp.points)) AS total_point, -- Pastikan nilai dibulatkan
                NOW() AS created_at,
                NOW() AS updated_at
            FROM 
                member_points mp
            GROUP BY 
                mp.user_id
            ON DUPLICATE KEY UPDATE total_point = VALUES(total_point), updated_at = VALUES(updated_at)
        ");

            // Update points in users table by deducting redeemed points from member_rewards
            DB::update("
            UPDATE users u
            JOIN (
                SELECT 
                    up.user_id, 
                    SUM(up.total_point) - COALESCE(SUM(CASE WHEN mr.status != 3 THEN mr.point ELSE 0 END), 0) AS final_points
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
                'catatan' => 'Update points due to recent changes in transactions or returns',
                'created_at' => Carbon::now(),
            ]);
        }
    }
}
