<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Library\ValidatedPermission;
use App\Models\FeePaymentMadeModel;
use App\Models\ReturPenjualanModel;
use Illuminate\Http\Request;

class ReturPenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.returpenjualan.table');
    }

    public function datasource()
    {
        if(!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_PENJUALAN)){
            return ;
        }

        return datatables(
            ReturPenjualanModel::view()
        )->make(true);
    }

    public function delete()
    {

    }
}
