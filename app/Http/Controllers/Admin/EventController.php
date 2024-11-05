<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Library\ValidatedPermission;
use App\Models\CabangModel;
use App\Models\EventGaleryModel;
use App\Models\EventsModel;
use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;

class EventController extends Controller
{
    public function index(){
        return view('admin.event.table');
    }

    public function datasource()
    {
        if (!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_EVENT)) {
            return [];
        }

        Carbon::setLocale('id');

        $query = DB::table('events AS e')
            ->select('e.*')
            ->selectRaw('GROUP_CONCAT(DISTINCT lm.level_name SEPARATOR ", ") AS member_names')
            ->selectRaw('COUNT(em.member_id) AS total_members')
            ->crossJoin(DB::raw('JSON_TABLE(e.member_id, "$[*]" COLUMNS (member_id_item VARCHAR(255) PATH "$")) AS j'))
            ->leftJoin('level_member AS lm', 'lm.id', '=', 'j.member_id_item')
            ->leftJoin('event_member AS em', 'em.event_id', '=', 'e.id')
            ->groupBy(DB::raw('e.id, e.start, e.end, e.judul, e.descriptions, e.publish, e.user_id, e.created_at, e.updated_at, e.member_id'));


        return DataTables::of($query)
            ->editColumn('start', function ($row) {
                return $row->start ? Carbon::parse($row->start)->translatedFormat('l, d M Y') : '';
            })
            ->editColumn('end', function ($row) {
                return $row->end ? Carbon::parse($row->end)->translatedFormat('l, d M Y') : '';
            })
            ->editColumn('member_names', function ($row) {
                if (empty($row->member_names)) {
                    return '<span class="badge bg-secondary">Tidak ada member</span>';
                }

                $names = explode(', ', $row->member_names);
                return collect($names)->map(function ($name) {
                    return '<span class="badge bg-primary">' . e($name) . '</span>';
                })->implode(' ');
            })
            ->rawColumns(['member_names'])
            ->toJson();
    }


    public function images($id){
        $r = EventGaleryModel::query()->find($id);
        if($r == null)return abort(404);

        if(\Storage::exists($r->path_file)){
            return response( \Storage::get($r->path_file), 200, [
                'Content-Type' => 'image/png'
            ] );
        }
        return abort(404);
    }

    public function delete(){
        if(!ValidatedPermission::authorize(ValidatedPermission::HAPUS_DATA_EVENT)){
            return ;
        }

        $id = \request('id');
        $r = EventsModel::query()->whereIn('id', $id)->delete();

        LogController::writeLog(ValidatedPermission::HAPUS_DATA_EVENT, 'Hapus Event', $id);
        return response()->json([
            'data'=>$r
        ]);
    }

    public function konfirmasi($token)
    {
        $data = explode('k0nf1m4', Crypt::decrypt($token));
        $event_id = $data[0];
        $id = $data[1];

        if (!EventsModel::find($event_id) || !UserModel::find($id)) {
            return abort(404);
        }

        $exists = DB::table('event_member')
            ->where('event_id', $event_id)
            ->where('member_id', $id)
            ->exists();

        if (!$exists) {
            $result = DB::table('event_member')->insert([
                'event_id' => $event_id,
                'member_id' => $id,
                'created_at' => Carbon::now(),
            ]);
            $data = [
                'nama'=>UserModel::find($id)->first_name.' '.UserModel::find($id)->last_name,
                'event'=>EventsModel::find($event_id)->judul,
                'text' => $result?'success':'danger',
                'button' => $result?'Berhasil':'Gagal',

            ];
            return view('admin.event.konfirmasi',$data) ;
        }

        $data2 = [
            'nama'=>UserModel::find($id)->first_name.' '.UserModel::find($id)->last_name,
            'event'=>EventsModel::find($event_id)->judul,
            'text' => 'success',
            'button' => 'Sudah Konfirmasi',

        ];

        return view('admin.event.konfirmasi',$data2);
    }

    public function konfirmasi_hadir($id)
    {
        $data = DB::table('event_member')
            ->where('event_id', $id)
            ->count();

        $event = EventsModel::find($id);
        $event->start = Carbon::parse($event->start)->locale('id')->isoFormat('D MMMM YYYY');
        $event->end = Carbon::parse($event->end)->locale('id')->isoFormat('D MMMM YYYY');

        if (!$data) {
            session()->flash('error', 'Data tidak tersedia');
            return redirect()->back();
        }

        return view('admin.event.hadir',compact('id', 'event'));
    }

    public function data_hadir($id)
    {
        $data = DB::table('event_member')
            ->leftJoin('users', 'users.id', '=', 'event_member.member_id')
            ->leftJoin('level_member', 'level_member.id', '=', 'users.level_member_id')
            ->select('event_member.*', 'users.first_name', 'users.last_name', 'level_member.level_name')
            ->where('event_member.event_id', $id)
            ->get();
        return DataTables::of($data)
            ->editColumn('nama', function ($data) {
                return $data->first_name . ' ' . $data->last_name;
            })
            ->editColumn('created_at', function ($data) {
                return Carbon::parse($data->created_at)->locale('id')->isoFormat('D MMMM YYYY H:mm:ss');
            })
            ->toJson();
    }

}
