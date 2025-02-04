<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Library\Helper;
use App\Models\ArticleCommentModel;
use App\Models\ArticleModel;
use App\Models\EventGaleryModel;
use App\Models\EventMember;
use App\Models\EventsModel;
use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class News extends Controller
{
    //
    public function index()
    {
        $cari = \request('cari', '');
        $kategori = \request('kat','');
//        var_dump($cari);die();
        $list =  DB::table('articles as a')
            ->select('a.*', 'b.category', 'c.first_name', 'c.last_name')
            ->leftJoin('article_categories as b', 'a.article_category_id', '=', 'b.id')
            ->leftJoin('users as c', 'a.pengelola_user_id', '=', 'c.id')
            ->orderBy('a.id', 'desc');
        if ($cari != '') {
            $list = Helper::whereFilter($list, ['a.judul', 'a.article'], $cari);
        }
        if ($kategori != '') {
            $list = Helper::whereFilter($list, ['a.article_category_id'], $kategori);
        }

        $r = $list->paginate(6);

//        var_dump($r);die();


        $populer = ArticleModel::query()->select('id','judul','path_images','hit')->orderBy('hit','desc')->limit(5)->get();
        $kategori = ArticleModel::query()
            ->select('articles.article_category_id', DB::raw('MAX(article_categories.category) as category'))
            ->leftJoin('article_categories', 'articles.article_category_id', '=', 'article_categories.id')
            ->groupBy('articles.article_category_id')
            ->get();

        $data = [
            'title'=> 'News',
            'news' => '',
            'list'=>$r,
            'cari'=>$cari,
            'populer'=>$populer,
            'kategori'=>$kategori
        ];

        return view('frontend.news.informasi', $data);
    }

    public function detail($id){
        $idd = Crypt::decrypt($id);
        $list =  DB::table('articles as a')
            ->select('a.*', 'b.category', 'c.first_name', 'c.last_name')
            ->leftJoin('article_categories as b', 'a.article_category_id', '=', 'b.id')
            ->leftJoin('users as c', 'a.pengelola_user_id', '=', 'c.id')
            ->where('a.id','=',$idd)
            ->orderBy('a.id', 'desc')
            ->first();




        $populer = ArticleModel::query()->select('id','judul','path_images','hit')->orderBy('hit','desc')->limit(5)->get();
        $kategori = ArticleModel::query()
            ->select('articles.article_category_id', DB::raw('MAX(article_categories.category) as category'))
            ->leftJoin('article_categories', 'articles.article_category_id', '=', 'article_categories.id')
            ->groupBy('articles.article_category_id')
            ->get();

        $comment = ArticleCommentModel::query()->where('article_id',$idd)->orderBy('created_at','desc')->get();

        ArticleModel::query()->where('id',$idd)->increment('hit');
        $data = [
            'title'=>'Detail Artikel',
            'news'=>'',
            'l'=>$list,
            'populer'=>$populer,
            'kategori'=>$kategori,
            'comment'=>$comment
,

        ];
        return view('frontend.news.det_informasi',$data);

    }
    public function image($id){
        $r = ArticleModel::find($id);
        if($r === null)return abort(404);
        if(!\Storage::exists($r->path_images)){
            return abort(404);
        }
        return response( \Storage::get($r->path_images), headers: [
            'Content-type' => 'image/png'
        ] );
    }
    public function postcomment(Request $request){
        $comment = $request->input('comment');
        $id = Crypt::decrypt($request->input('id'));
        $user_id = session('user')->id;

        $data = [
            'article_id'=>$id,
            'comment'=>$comment,
            'users_id'=>$user_id,
            'created_at'=>Carbon::now('Asia/Jakarta')
        ];
        $result = DB::table('article_comments')->insert($data);

        if ($result){
            Session::flash('success','Komen Berhasil !');
        }else{
            Session::flash('error','Komen Gagal');
        }
        return redirect()->back();

    }


    public function event()
    {
        $cari = \request('cari','');


        $list=EventsModel::query()
            ->select('events.*','users.first_name','users.last_name')
            ->leftJoin('users', 'events.user_id','=','users.id')
            ->where('member_id', 'LIKE', '%'.session('user')->level_member_id.'%')
            ->where('publish', 1);

        if ($cari != '') {
            $list = Helper::whereFilter($list, ['judul', 'descriptions'], $cari);
        }
        $r = $list->paginate(6);

//        var_dump($r);die();

        $data = [
            'title'=>'Event',
            'news' => '',
            'list'=>$r,
            'cari'=>$cari
        ];

        return view('frontend.news.event', $data);
    }

    public function detevent($id){
        $idd = Crypt::decrypt($id);
        $list = EventsModel::query()
            ->select('events.*','event_galeries.path_file', 'event_galeries.id as galeri_id')
            ->leftJoin('event_galeries', 'events.id','=','event_galeries.event_id')
            ->where('events.id',$idd)
            ->orderBy('event_galeries.path_file','desc')
            ->get();

        $cek = EventMember::query()->where('event_id', $idd)->where('member_id', session('user')->id)->get();
//        dd($list);die();

        $data = [
            'title'=>'Event Detail',
            'news'=>'',
            'list'=>$list,
            'cek'=>$cek

        ];

        return view('frontend.news.det_event',$data);

    }
    public function joinevent($eventId) {
        
        $cek = EventMember::query()->where('event_id', $eventId)->where('member_id', session('user')->id)->get();
        if ($cek->isNotEmpty()) {
            return response()->json(['message' => 'Maaf, Anda sudah mengikuti event ini.'], 500);
        }
        
        try {
            $data = [
                'event_id' => $eventId,
                'member_id' => session('user')->id,
                'created_at' => now()->setTimezone('Asia/Jakarta'),
            ];

            EventMember::query()->insert($data);

            return response()->json(['message' => 'Berhasil mengikuti event.']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Gagal mengikuti event.'], 500);
        }
    }
    public function imageevent($id){

        $r = EventGaleryModel::query()->where('event_id',$id)->orderBy('path_file','desc')->first();
        if(!\Storage::exists($r->path_file)){
            return abort(404);
        }
        return response( \Storage::get($r->path_file), headers: [
            'Content-type' => 'image/png'
        ] );

    }
    public function imageeventdetail($id){

        $r = EventGaleryModel::query()->where('id',$id)->orderBy('path_file','desc')->first();
        if(!\Storage::exists($r->path_file)){
            return abort(404);
        }
        return response( \Storage::get($r->path_file), headers: [
            'Content-type' => 'image/png'
        ] );

    }

}
