<?php

namespace App\Jobs;

use App\Models\LevelMemberModel;
use App\Models\TransactionModel;
use App\Models\UserModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;


class CalcMemberExpenseJob_xxx implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('CalcMemberExpenseJob executed at: ' . now());
        $sql = "update users
                left join ( SELECT member_user_id, sum(dpp_amount)as total from transactions WHERE YEAR(tgl_invoice)=?
                            GROUP BY member_user_id
                        )as tbl on users.id=tbl.member_user_id
                set users.total_spent = tbl.total
                where users.id=tbl.member_user_id";
        echo "Jalan hitung";
        \DB::update($sql, [date('Y')]);

        $sql = "DELETE FROM member_spent WHERE tahun=?";
        \DB::delete($sql, [date('Y')]);

        $sql = "INSERT INTO member_spent (user_id, tahun, total_spent, created_at)
        SELECT id, ?, total_spent, NOW() FROM users";
        \DB::insert($sql, [date('Y')]);


        $lvlmember = LevelMemberModel::query()->orderBy('level', 'asc')->get();
        $lvls = [];
        for ($i = 0; $i < count($lvlmember) - 1; $i++) {
            $lvl = $lvlmember[$i];
            $next = $lvlmember[$i + 1];
            UserModel::query()
                ->whereBetween('total_spent', [$lvl->limit_transaction, $next->limit_transaction])
                ->update(['level_member_id' => $next->id]);
        }
    }
}
