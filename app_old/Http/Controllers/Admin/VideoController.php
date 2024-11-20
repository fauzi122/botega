<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Library\ValidatedPermission;
use App\Models\SliderModel;
use App\Models\YoutubeModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{

    public function index(){

        return view('admin.youtube.table');
    }

    public function datasource(){
        if(!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_VIDEO_YOUTUBE)){
            return [];
        }

        return datatables(YoutubeModel::query() )->toJson();
    }

    public function delete(){

        if(!ValidatedPermission::authorize(ValidatedPermission::HAPUS_DATA_VIDEO_YOUTUBE)){
            return ;
        }

        $id = \request('id');
        $r = YoutubeModel::query()->whereIn('id', $id)->delete();
        LogController::writeLog(ValidatedPermission::HAPUS_DATA_VIDEO_YOUTUBE, 'Hapus video youtube ', $id);
        return response()->json([
            'data'=>$r
        ]);
    }
}
