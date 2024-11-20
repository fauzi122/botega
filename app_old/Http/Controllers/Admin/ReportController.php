<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ReportFeeCategory;
use App\Exports\ReportFeeMember;
use App\Exports\ReportFeeMerk;
use App\Http\Controllers\Controller;
use App\Models\FeeProfessionalModel;
use App\Models\ProductCategoryModel;
use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    public function feeMember()
    {
        return view('admin.report.fee-member');
    }

    public function feeKategori(){
        return view('admin.report.fee-kategori');
    }

    public function feeMerkBarang(){
        return view('admin.report.fee-merk-barang');
    }

    public function feeMemberShow(){
        $member_user_id = \request('users_id');
        $periode_awal = \request('periode_awal');
        $periode_akhir = \request('periode_akhir');

        $model =  FeeProfessionalModel::view()->where("member_user_id", $member_user_id )
                    ->whereBetween("dt_acc", [Carbon::parse($periode_awal)->format("Y-m-d"), Carbon::parse($periode_akhir)->format("Y-m-d")], );

        return view('admin.report.show-fee-member', [
            "user" => UserModel::where("id", $member_user_id)->first(),
            "periode_awal" => $periode_awal,
            "periode_akhir" => $periode_akhir,
            "data" => $model->get()

        ]);
    }

    public function feeMemberXLS(){
        $member_user_id = \request('member_user_id');
        $periode_awal = \request('periode_awal');
        $periode_akhir = \request('periode_akhir');

        $model =  FeeProfessionalModel::view()->where("member_user_id", $member_user_id )
                    ->whereBetween("dt_acc", [Carbon::parse($periode_awal)->format("Y-m-d"), Carbon::parse($periode_akhir)->format("Y-m-d")], );

        $data = [
            "user" => UserModel::where("id", $member_user_id)->first(),
            "periode_awal" => $periode_awal,
            "periode_akhir" => $periode_akhir,
            "data" => $model->get()
        ];
        if($data['data']->count() == 0){
            return redirect()->back()->with("error", "Data Tidak Ditemukan");
        }
        return (new ReportFeeMember($data))->download("fee-member-".$data['user']->first_name.".xlsx");
    }

    public function feeKategoriShow(){
        $category_id = \request('category_id');
        $periode_awal = \request('periode_awal');
        $periode_akhir = \request('periode_akhir');


        $model =  FeeProfessionalModel::view()->where("category_id", $category_id )
            ->whereBetween("dt_acc", [Carbon::parse($periode_awal)->format("Y-m-d"), Carbon::parse($periode_akhir)->format("Y-m-d")], );

        $data = [
            "kategori" => ProductCategoryModel::where("id", $category_id)->first(),
            "periode_awal" => $periode_awal,
            "periode_akhir" => $periode_akhir,
            "data" => $model->get()

        ];
        return view('admin.report.show-fee-kategori', $data);
    }

    public function feeKategoriXLS(){
        $category_id = \request('category_id');
        $periode_awal = \request('periode_awal');
        $periode_akhir = \request('periode_akhir');

        $model =  FeeProfessionalModel::view()->where("category_id", $category_id )
            ->whereBetween("dt_acc", [Carbon::parse($periode_awal)->format("Y-m-d"), Carbon::parse($periode_akhir)->format("Y-m-d")], );

        $data = [
            "kategori" => ProductCategoryModel::where("id", $category_id)->first(),
            "periode_awal" => $periode_awal,
            "periode_akhir" => $periode_akhir,
            "data" => $model->get()
        ];

        if($data['data']->count() == 0){
            return redirect()->back()->with("error", "Data Tidak Ditemukan");
        }
        return (new ReportFeeCategory($data))?->download("fee-category.xls");

    }



    public function feeMerkBarangShow(){
        $category_id = \request('category_id');
        $merk = trim(\request('merk'));
        $periode_awal = \request('periode_awal');
        $periode_akhir = \request('periode_akhir');

        $model =  FeeProfessionalModel::view()->where("category_id", $category_id )
                    // ->where("merk", $merk)
                    ->whereBetween("dt_acc", [Carbon::parse($periode_awal)->format("Y-m-d"), Carbon::parse($periode_akhir)->format("Y-m-d")], );
                    // ->groupBy("merk");
        if($merk != null || $merk != ''){
            $model->where("merk", $merk);
        }

        $dt = $model->get();
        $data = [
            "kategori" => ProductCategoryModel::where("id", $category_id)->first(),
            "periode_awal" => $periode_awal,
            "periode_akhir" => $periode_akhir,
            "merk" =>  $model->groupBy("merk")->get()->pluck("merk")->toArray(),
            "data" => $dt,
        ];
        if($data['data']->count() == 0){
            return redirect()->back()->with("error", "Data Tidak Ditemukan");
        }
        return view('admin.report.show-fee-merk', $data);
    }

    public function feeMerkBarangXLS(){
        $category_id = \request('category_id');
        $merk = trim(\request('merk'));
        $periode_awal = \request('periode_awal');
        $periode_akhir = \request('periode_akhir');

        $model =  FeeProfessionalModel::view()->where("category_id", $category_id )
                    // ->where("merk", $merk)
                    ->whereBetween("dt_acc", [Carbon::parse($periode_awal)->format("Y-m-d"), Carbon::parse($periode_akhir)->format("Y-m-d")], );
                    // ->groupBy("merk");
        if($merk != null || $merk != ''){
            $model->where("merk", $merk);
        }

        $dt = $model->get();
        $data = [
            "kategori" => ProductCategoryModel::where("id", $category_id)->first(),
            "periode_awal" => $periode_awal,
            "periode_akhir" => $periode_akhir,
            "merk" =>  $model->groupBy("merk")->get()->pluck("merk")->toArray(),
            "data" => $dt,
        ];
        if($data['data']->count() == 0){
            return redirect()->back()->with("error", "Data Tidak Ditemukan");
        }

        return (new ReportFeeMerk($data))?->download("fee-merk.xls");

    }
}
