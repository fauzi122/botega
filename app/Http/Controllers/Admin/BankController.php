<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Library\ValidatedPermission;
use App\Models\BankModel;
use App\Models\CabangModel;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function index(){
        return view('admin.bank.table');
    }


    public function datasource(){
        if(!ValidatedPermission::authorize('Bank.Read')){
            return [];
        }

        $id = \request('id');
        return datatables(BankModel::query())->make(true);
    }

    public function delete(){
        if(!ValidatedPermission::authorize('Bank.Delete')){
            return;
        }

        $id = \request('id');
        $banks = BankModel::query()->whereIn('id', $id)->get();
        foreach($banks as $bank){
            try{\Storage::delete($bank->logo_path);}catch (\Exception $exception){}
        }

        $r = BankModel::query()->whereIn('id', $id)->delete();

        LogController::writeLog(ValidatedPermission::HAPUS_DATA_BANK, 'Hapus data bank', $id);

        return response()->json([
            'data'=>$r
        ]);
    }

    public function logo($id){
        $bank = BankModel::find($id);
        if(!\Storage::exists($bank?->logo_path ?? '-')){
            return response( file_get_contents('assets/images/icons/bank.png'), 200, [
               'Content-Type' => 'image/png'
            ]);
        }
        return response( \Storage::get($bank->logo_path), 200, [
            'Content-Type' => 'image/png'
        ]);
    }
}
