<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Library\ValidatedPermission;
use App\Models\LogsModel;
use App\Models\MemberPointModel;
use App\Models\MemberRewardModel;
use App\Models\RedeemModel;
use App\Models\TransactionModel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RedeemController extends Controller
{
    public function index(){
        LogsModel::whereRaw('actions LIKE ?',['Klaim reward%'])->update(['status'=>1]);
        return view('admin.redeempoint.table');
    }


    public function datasource($state = ''){
        $id = \request('id');
        Carbon::setLocale('id');
        $mapstt = [''=>0, 'proses'=>1, 'acc'=>2, 'tolak'=>3];
        $v = MemberRewardModel::view()->where('status', $mapstt[$state] ?? 0);

        return datatables( $v )
            ->addColumn('member', function($b){
                return $b['first_name'] . ' ' . $b['last_name'];
            })
            ->editColumn('created_at', function($row){
                if($row['created_at'] == '')return '';
                return Carbon::parse($row['created_at'])->translatedFormat('l, d M Y');
            })
            ->editColumn('approved_at', function($row){
                if($row['approved_at'] == '')return '';
                return Carbon::parse($row['approved_at'])->translatedFormat('l, d F Y');
            })
            ->toJson(true);
    }

    public function delete()
    {
        $id = \request('id');
        $r = MemberPointModel::query()->whereIn('id', $id)->delete();
        LogController::writeLog(ValidatedPermission::HAPUS_DATA_REDEEM_POINT, 'Hapus data Redeem Point', $id);
        return response()->json([
            'data' => $r
        ]);
    }
}
