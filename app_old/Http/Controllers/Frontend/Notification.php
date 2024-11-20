<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Library\Helper;
use App\Models\LogsModel;
use Crypt;
use Redirect;
use Session;
use function request;

class Notification extends Controller
{
    //
    public function index()
    {

        $cari = request('cari','');
        $user_id = session('user')->id;
        $list = LogsModel::query()
            ->where('user_id', $user_id)
            ->where('status', 0)
            ->orderBy('id','desc');

        if ($cari != '') {
            $list = Helper::whereFilter($list, ['actions'], $cari);
        }
        $m = $list->paginate(10);
        $data = [
            'title' => 'Notifications',
            'list' => $m,
            'cari'=>$cari
        ];
        return view('frontend.home.notifications', $data);
    }

    public function baca()
    {

        $cari = request('cari','');
        $user_id = session('user')->id;
        $list = LogsModel::query()
            ->where('user_id', $user_id)
            ->orderBy('id','desc');

        if ($cari != '') {
            $list = Helper::whereFilter($list, ['actions'], $cari);
        }
        $m = $list->paginate(10);
        $data = [
            'title' => 'Notifications',
            'list' => $m,
            'cari'=>$cari
        ];
        return view('frontend.home.notificationsnew', $data);
    }

    public function read()
    {
        $cari = request('cari','');
        $user_id = session('user')->id;
        $list = LogsModel::query()
            ->where('user_id', $user_id)
            ->where('status', 1)
            ->orderBy('id','desc');

        if ($cari != '') {
            $list = Helper::whereFilter($list, ['actions'], $cari);
        }
        $m = $list->paginate(10);
        $data = [
            'title' => 'Notifications',
            'list' => $m,
            'cari'=>$cari
        ];
        return view('frontend.home.notifications_read', $data);
    }

    public function ceknotif($id)
    {
        $idd = Crypt::decrypt($id);

        $t = LogsModel::query()->where('id', $idd)->first();


        if ($t) {
            LogsModel::query()->where('id', $idd)->update(['status' => 1]);
            $decode = json_decode($t->payload);
            if(isset($decode->url)) {
                return redirect()->to($decode->url);
            } else {
                return redirect()->back();
            }
        }
        Session::flash('warning', 'Mohon Maaf link tidak tersedia');
        return redirect()->back();
    }

    public function datanotif(){
        $n = LogsModel::view()
            ->orderBy('created_at', 'desc')
            ->where('status',0)
            ->where('user_id',session('user')->id)
            ->limit(10)->get();
        $ret = [];

        foreach($n as $r){
            $r->link = $r->actions == "Update Profile" ? url('admin/approval') : (
            substr($r->actions,0,12 ) == 'Klaim reward' ? url('admin/redeem' ) : ''
            );
            $r->foto_path = \Storage::exists($r->foto_path ?? '-') ? url('admin/member/foto/'.$r->user_id.'.png') : '';
            $r->url = url('ceknotif/'.\Illuminate\Support\Facades\Crypt::encrypt($r->id));
            $ret[] = $r;
        }
        return response()->json([
            'data'=>$ret
        ]);

    }
}
