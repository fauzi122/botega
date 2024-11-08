<?php

namespace App\Livewire\Admin\Paymentmade;

use App\Http\Controllers\Admin\LogController;
use App\Library\ValidatedPermission;
use App\Models\DetailDeliveryOrderModel;
use App\Models\FeePaymentMadeModel;
use App\Models\ProductModel;
use App\Models\ProsesHistoryModel;
use App\Models\TransactionModel;
use Carbon\Carbon;
use Livewire\Component;

class Form extends Component
{
    public $id;

    public $editform = false;
    public $no_so;
    public $no_sj;
    public $no_inv;
    public $member;
    public $member_user_id;
    public $nominal;
    public $nominal_hutang;
    public $fee_date;
    public $listsj = [];
    public $listInv = [];

    public function gantiSJ(){
        $this->no_inv = null;
    }

    public function pilihSO($idpenjualan){
        $t = TransactionModel::query()->where('id', $idpenjualan)->first();
        if($t === null){return;}
        $this->no_so = $t->nomor_so;
        $this->no_sj = null;
        $this->no_inv = null;
    }

    public function edit($id){
        $e = FeePaymentMadeModel::view()->where('id', $id)->first();

        $this->editform = $e != null;
        $this->id = $id;
        $this->no_so = $e?->no_so ?? '';
        $this->no_sj = $e?->no_sj ?? '';
        $this->no_inv = $e?->no_inv ?? '';
        $this->member_user_id = $e?->member_user_id ?? '';

        $this->member = $e==null ? '' : '('.$e?->id_no.') '. ($e?->first_name ?? '') . ' ' . ($e?->last_name);
        $this->fee_date = $e?->fee_date ?? '';
        $this->nominal = $e?->nominal ?? 0;
        $this->nominal_hutang = $e?->nominal_hutang ?? 0;

    }

    public function newForm(){
        $this->edit(0);
    }


    private function validasi(){
        $v =   $this->validate([
            'member_user_id' => 'required|exists:users,id',
            'nominal' => 'required|min:1',
        ],[
            'member_user_id' => [
                'required' => 'Member harus dipilih',
                'exists' => 'Member tidak dikenal'
            ],
            'nominal' => 'nominal harus diisikan',
        ]);
        $v['no_so'] = $this->no_so;
        $v['no_sj'] = $this->no_sj;
        $v['no_inv'] = $this->no_inv;
        $v['user_id'] = session('admin')->id;
        $v['keterangan'] = 'Fee yang telah dibayarkan untuk SO: '.$this->no_so;
        $v['fee_date'] = strlen($this->fee_date) <10 ? null : Carbon::parse( $this->fee_date )?->format('Y-m-d');

        return $v;
    }

    public function store(){
        if($this->editform){
            $this->update();
        }else{
            $this->save();
        }

    }

    private function save(){
        if(!ValidatedPermission::authorize(ValidatedPermission::TAMBAH_DATA_FEE)){
            return ;
        }

        $v = $this->validasi();
        $v['created_at'] = Carbon::now();

        try {
            $m = FeePaymentMadeModel::query()->insert($v);
            session()->flash('success', 'Data berhasil di simpan');
            $this->dispatch('refresh');
            $this->edit($m);
            LogController::writeLog(ValidatedPermission::TAMBAH_DATA_FEE, 'Tambah data payment made ', $v);
        }catch (\Exception $e){
            session()->flash('error', 'Data Gagal disimpan'.$e->getMessage());
        }
    }

    public function update(){
        if(!ValidatedPermission::authorize(ValidatedPermission::UBAH_DATA_FEE)){
            return ;
        }

        $v = $this->validasi();
        $v['updated_at'] = Carbon::now();

        try {
            $m = FeePaymentMadeModel::query()->where('id', $this->id)->update($v);
            session()->flash('success', 'Data berhasil diubah');
            $this->dispatch('refresh');
            LogController::writeLog(ValidatedPermission::UBAH_DATA_FEE, 'Ubah data produk ', $v);
        }catch (\Exception $e){
            session()->flash('error', 'Data Gagal diubah'.$e->getMessage());
        }
    }

    private function isilist(){
        $this->listsj = DetailDeliveryOrderModel::query()->where('number_so', $this->no_so)
            ->groupBy('number_sj')->get(['number_sj']);
        $this->listInv = DetailDeliveryOrderModel::query()->where([
            'number_so' => $this->no_so,
            'number_sj' => $this->no_sj
        ])->groupBy('number_in')->get(['number_in']);

    }

    public function render()
    {
        $this->isilist();
        return view('livewire.admin.paymentmade.form');
    }
}
