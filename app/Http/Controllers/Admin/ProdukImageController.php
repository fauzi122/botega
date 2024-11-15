<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Libraries\QueryBuilderExt;
use App\Models\ProductImageModel;
use App\Models\ProductModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukImageController extends Controller
{
    public function index($id)
    {
        $produk = ProductModel::query()->find($id);
        return view('admin.produkimage.table', [
            'produk' => $produk
        ]);
    }

    public function image($id)
    {
        $pi = ProductImageModel::find($id);
        if ($pi == null) abort(404);

        $fn = 'produk/' . $pi->id . '.png';
        if (!Storage::exists($fn)) {
            abort(404);
        }
        $content = Storage::get($fn);
        return response($content, headers: [
            'Content-type' => 'image/png'
        ]);
    }

    public function datasource()
    {
        $id = \request('id');
        $origin = datatables(ProductImageModel::query()->where('product_id', $id))->toArray();
        //        $js = json_decode( json_encode($r), true );
        //        $origin = $js['original'];

        $data = [];
        foreach ($origin['data'] as $r) {
            if (Storage::exists($r['path_file'] ?? '--')) {
                $r['path_file'] = url('admin/produk-image/image/' . $r['id'] . '.png');
            }

            $data[] = $r;
        }
        $origin['data'] = $data;
        return $origin;
    }

    private function hapusimage()
    {
        $id = \request('id');
        $imgs = ProductImageModel::query()->whereIn('id', $id)->get();
        foreach ($imgs as $img) {
            try {
                Storage::delete($img->path_file);
            } catch (\Exception $e) {
            }
        }
    }

    public function delete()
    {
        $id = \request('id');
        $this->hapusimage();
        $r = ProductModel::query()->whereIn('id', $id)->delete();
        return response()->json([
            'data' => $r
        ]);
    }
}
