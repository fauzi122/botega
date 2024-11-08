<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Library\ValidatedPermission;
use App\Models\DetailTransactionModel;
use App\Models\FeePaymentMadeModel;
use App\Models\ProductModel;
use Illuminate\Http\Request;

class PaymentMadeController extends Controller
{
    public function index(){
        return view('admin.payment-made.table');
    }

    public function datasource(){

        if(!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_FEE)){
            return ;
        }

        return datatables(
            FeePaymentMadeModel::view()
        )->make(true);
    }


    public function delete(){
        if(!ValidatedPermission::authorize(ValidatedPermission::HAPUS_DATA_FEE)){
            return ;
        }

        $id = \request('id');
        $r = FeePaymentMadeModel::query()->whereIn('id', $id)->delete();
        LogController::writeLog(ValidatedPermission::HAPUS_DATA_FEE, 'Hapus data payment made ', $id);
        return response()->json([
            'data'=>$r
        ]);
    }

}
