<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Library\ValidatedPermission;
use App\Models\KatalogProduk;
use App\Models\KatalogProdukModel;
use App\Models\ProductImageModel;
use App\Models\ProductModel;
use App\Models\SliderModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KatalogProdukController extends Controller
{

    public function index()
    {

        return view('admin.katalogproduk.table');
    }

    public function image($id)
    {
        $pi = KatalogProdukModel::find($id);
        if ($pi == null) abort(404);

        $fn =  $pi->gambar_katalog;
        if (!Storage::exists($fn) && $fn != '') {
            abort(404);
        }
        $content = Storage::get($fn);
        return response($content, headers: [
            'Content-type' => 'image/png'
        ]);
    }


    public function berkas($id)
    {
        $pi = KatalogProdukModel::find($id);
        if ($pi == null) abort(404);

        $fn =  $pi->file_katalog;
        if (!Storage::exists($fn) && $fn != '') {
            abort(404);
        }
        $content = Storage::get($fn);
        return response($content, headers: [
            'Content-type' => 'application/pdf'
        ]);
    }

    public function datasource()
    {

        if (!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_KATALOG)) {
            return [];
        }

        return datatables(KatalogProdukModel::query())
            ->addColumn('urlfoto', function ($v) {
                $f = secure_url('/admin/katalog-produk/image/' . $v['id'] . '.png');
                if (Storage::exists($v['gambar_katalog']) && $v['gambar_katalog'] != '') {
                    return $f;
                }
                return '';
            })
            ->addColumn('fileunduh', function ($v) {
                $f = secure_url('/admin/katalog-produk/berkas/' . $v['id'] . '.pdf');
                if (Storage::exists($v['file_katalog']) && $v['file_katalog'] != '') {
                    return $f;
                }
                return '';
            })
            ->editColumn('created_at', function ($v) {
                if ($v['created_at'] === '' || $v['created_at'] === null) return null;
                return Carbon::parse($v['created_at'])->diffForHumans();
            })
            ->editColumn('updated_at', function ($v) {
                if ($v['updated_at'] === '' || $v['updated_at'] === null) return null;
                return Carbon::parse($v['updated_at'])->diffForHumans();
            })
            ->toJson();
    }

    private function hapusimage()
    {
        $id = \request('id');
        $imgs = KatalogProdukModel::query()->whereIn('id', $id)->get();
        foreach ($imgs as $img) {
            try {
                Storage::delete($img->gambar_katalog);
            } catch (\Exception $e) {
            }
            try {
                Storage::delete($img->file_katalog);
            } catch (\Exception $e) {
            }
        }

        LogController::writeLog(ValidatedPermission::UBAH_DATA_KATALOG, 'Hapus gambar katalog produk', $id);
    }

    public function delete()
    {

        if (!ValidatedPermission::authorize(ValidatedPermission::HAPUS_DATA_KATALOG)) {
            return;
        }

        $id = \request('id');
        $this->hapusimage();
        $r = KatalogProdukModel::query()->whereIn('id', $id)->delete();
        LogController::writeLog(ValidatedPermission::HAPUS_DATA_KATALOG, 'Hapus katalog produk ', $id);
        return response()->json([
            'data' => $r
        ]);
    }
}
