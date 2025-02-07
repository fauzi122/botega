<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Library\Helper;
use App\Models\ProductCommentModel;
use App\Models\ProductModel;
use Carbon\Carbon;
use Crypt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class Product extends Controller
{
    //
    public function index()
    {
        $cari = \request('cari', '');
        $sort = \request('sort', '');
        $user_id = session('user') ? session('user')->id : '';

        $list = ProductModel::query()->select('products.*', 'product_categories.category', 'product_likes.id as islike', 'product_images.path_file')->leftJoin('product_categories', 'products.category_id', '=', 'product_categories.id')->leftJoin('product_likes', function ($join) use ($user_id) {
            $join->on('products.id', '=', 'product_likes.product_id')->where('product_likes.user_id', $user_id);
        })->leftJoin('product_images', function ($join) {
            $join->on('products.id', '=', 'product_images.product_id')->where('product_images.is_primary', '=', 1);
        })->where('sts_product', 1)->orderBy('products.id', 'desc');
//test
        if ($cari != '') {
            $list = Helper::whereFilter($list, ['products.name', 'products.descriptions', 'product_categories.category'], $cari);
        }
        $r = $list->paginate(12);

        $data = ['title' => 'Product', 'product' => '', 'list' => $r, 'cari' => $cari

        ];
        return view('frontend.product.product', $data);
    }

    public function like_product(Request $r)
    {
        $productId = $r->input('postId');
        $isLikedParam = $r->input('isLiked');
        $userId = session('user')->id;

        $isLiked = DB::table('product_likes')->where('product_id', $productId)->where('user_id', $userId)->exists();
        $produk = DB::table('products')->find($productId);

        if ($isLiked) {
            $result = DB::table('product_likes')->where('product_id', $productId)->where('user_id', $userId)->delete();
            if ($produk->likes > 0) {
                $produk = DB::table('products')->where('id', $productId)->decrement('likes');
            }
        } else {
            // Jika belum menyukai, tambahkan like
            $data = ['product_id' => $productId, 'user_id' => $userId];
            $result = DB::table('product_likes')->insert($data);
            $produk = DB::table('products')->where('id', $productId)->increment('likes');

        }
        $pu = DB::table('products')->find($productId);
        $likeupdate = $pu->likes;
        $likeChange = $isLikedParam ? -1 : 1;


        return response()->json(['likes' => $likeupdate, 'status' => $isLiked, 'result' => $result, 'isLiked' => $likeChange]);
    }

    public function productdetail($id)
    {

        $productId = Crypt::decrypt($id);


        $result = DB::table('products as a')->select('a.*', 'b.category', 'c.id as idimage', 'c.path_file', 'c.is_primary', 'c.name as nameimage')->leftJoin('product_categories as b', 'a.category_id', '=', 'b.id')->leftJoin('product_images as c', 'c.product_id', '=', 'a.id')->where('a.id', '=', $productId)->orderByDesc('c.is_primary')->get();

//        var_dump($productId);die();

        $komentar = ProductCommentModel::query()->where('product_id', $result[0]->id)->get();


        $data = ['product' => '', 'result' => $result, 'title' => 'Product Detail', 'komentar' => $komentar

        ];

        return view('frontend.product.product-detail', $data);
    }

    public function komentarProduk(Request $requestr)
    {

        $komen = $requestr->input('reviewComment');
        $id = Crypt::decrypt($requestr->input('id'));
        $user_id = session('user')->id;
        $data = ['comment' => $komen, 'product_id' => $id, 'users_id' => $user_id, 'created_at' => Carbon::now('Asia/Jakarta')];

        $result = ProductCommentModel::query()->insert($data);
        if ($result) {
            Session::flash('success', 'Komentar Berhasil !');
        } else {
            Session::flash('error', 'Komentar Gagal');
        }

        return Redirect::back();
    }

}
