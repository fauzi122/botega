<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Library\ValidatedPermission;
use App\Models\GiftModel;
use App\Models\GiftTypeModel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GiftController extends Controller
{

    public function index(){
        return view('admin.gift.table');
    }



    public function datasource(){
        if(!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_HADIAH)){
            return [] ;
        }

        $id = \request('id');
        Carbon::setLocale('id');

        return datatables(GiftModel::view())
                ->editColumn("sent_at", function($e){
                    if($e['sent_at'] == '')return '';
                    return Carbon::parse($e['sent_at'])->translatedFormat('l, d F Y');
                })
                ->editColumn("received_at", function($e){
                    return $e['received_at'];
                    if($e['received_at'] == '')return '';
                    return Carbon::parse($e['received_at'])->translatedFormat('l, d F Y');
                })
                ->toJson();
    }

    public function delete(){
        if(!ValidatedPermission::authorize(ValidatedPermission::HAPUS_DATA_HADIAH)){
            return ;
        }

        $id = \request('id');
        $r = GiftModel::query()->whereIn('id', $id)->delete();
        LogController::writeLog(ValidatedPermission::HAPUS_DATA_HADIAH, 'Hapus data  hadiah', $id);
        return response()->json([
            'data'=>$r
        ]);
    }
}
