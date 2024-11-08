<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogsModel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotificationController extends Controller
{

    public function index(){
        $n = LogsModel::view()
                ->orderBy('created_at', 'desc')
                ->where('status',0)
                ->where('user_type','member')
                ->whereDate('created_at', '>', Carbon::now()->subDays(7))
                ->limit(10)->get();
        $ret = [];
        $mapaction = [
            'Update Profile' => url('admin/approval'),
            'Update Profile' => url('admin/approval'),
        ];
        foreach($n as $r){
            $r->link = $r->actions == "Update Profile" ? url('admin/approval') : (
                substr($r->actions,0,12 ) == 'Klaim reward' ? url('admin/redeem' ) : url('admin/log')
            );
            $r->foto_path = \Storage::exists($r->foto_path ?? '-') ? url('admin/member/foto/'.$r->user_id.'.png') : '';
            $ret[] = $r;
        }
        return response()->json([
            'data'=>$ret
        ]);

    }
}
