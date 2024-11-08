<?php

namespace App\Livewire\Admin\Penjualan;

use App\Models\DetailTransactionModel;
use App\Models\LevelMemberModel;
use App\Models\ProductModel;
use App\Models\RequestUpdateModel;
use App\Models\TransactionModel;
use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Form extends Component
{

    protected $listeners = ['edit', 'newForm'];

    public $trx_at;
    public $invoice_no;
    public $member_user_id;
    public $pengelola_user_id;
    public $total;
    public $notes;
    public $point;
    public $editform = false;
    public $lm;
    public $member;
    public $product_id;
    public $sale_price;
    public $qty;
    public $nomor_so;


    public function mount($id){
        $this->edit($id);
    }

    public function edit($id){
        $this->lm = TransactionModel::view()->find($id);
        $this->editform = $this->lm != null;
        $this->trx_at = $this->lm?->trx_at ?? Carbon::now()->format('Y-m-d');
        $this->invoice_no = $this->lm?->invoice_no ?? '';
        $this->nomor_so = $this->lm?->nomor_so ?? '';
        $this->member_user_id = $this->lm?->member_user_id;
        $this->pengelola_user_id = $this->lm?->pengelola_user_id;
        $this->total = $this->lm?->total ?? 0;
        $this->notes = $this->lm?->notes ?? '';
        $this->point = $this->lm?->point ?? 0;
        $this->member = null;

        if($this->member_user_id != null){
            $this->member = UserModel::query()->where('id', $this->member_user_id)->first();
        }
        $this->dispatch('onEdit');
    }

    public function newForm(){
        $this->edit(0);
    }

    private function simpanDetail(){
        $v = $this->validate([
            'product_id' => 'required|exists:products,id',
            'sale_price' => 'required|min:0',
            'qty' => 'required|min:0'
        ],[
            'product_id' => 'Produk tidak valid',
            'sale_price' => 'Harga produk harus diisikan',
            'qty' => 'Qty penjualan harus diisikan'
        ]);
        $product = ProductModel::where('id', $this->product_id)->first();
        $v['transaction_id'] = $this->lm?->id;
        $v['cost_price'] = $product?->cost_price;
//        $v['user_id'] = session('user')?->id;
        $v['created_at'] = Carbon::now();

        try {
            DetailTransactionModel::insert($v);
            $this->hitungTotal();
            $this->dispatch('refresh');
        }catch (\Exception $e){
            session()->flash('error', 'Gagal simpan '.$e->getMessage());
        }
    }

    private function hitungTotal(){
        $this->total = DetailTransactionModel::where('transaction_id', $this->lm?->id)->sum(DB::raw('qty * sale_price'));
        TransactionModel::where('id', $this->lm?->id)->update(['total' => $this->total]);
    }

    private function validasi(){
        $v =   $this->validate([
            'trx_at' => 'required',
            'invoice_no' => 'required|unique:transactions,invoice_no,'.$this->lm?->id,
            'member_user_id' => 'required',
        ],[
            'trx_at' => 'Tanggal transaksi harus diisi',
            'invoice_no' => [
                'required' => 'Invoice harus diisikan',
                'unique' => 'No invoice sudah ada, gunakan nomor lainnya'
            ],
            'member_user_id' => 'Member harus diisikan'
        ]);
        $user = session('user');
        $v['pengelola_user_id'] = $user?->id;
        $v['total'] = $this->total;
        $v['point'] = $this->point;
        $v['notes'] = $this->notes;

        return $v;
    }

    public function save(){
        if($this->editform){
            $this->update();
        }else{
            $this->store();
        }
        $this->simpanDetail();
    }

    public function store(){
        $v = $this->validasi();
        $v['created_at'] = Carbon::now();
        try {
            $m = TransactionModel::query()->insert($v);
            $lastid = DB::getPdo()->lastInsertId();
            $this->edit($lastid);
            session()->flash('success', 'Data berhasil di simpan');
        }catch (\Exception $e){
            session()->flash('error', 'Data Gagal disimpan'.$e->getMessage());
        }
    }

    public function update(){
        $v = $this->validasi();
        $v['updated_at'] = Carbon::now();
        try {
            $m = TransactionModel::query()->where('id', $this->lm->id)->update($v);
            session()->flash('success', 'Data berhasil diubah');

            $this->edit($this->lm?->id);
        }catch (\Exception $e){
            session()->flash('error', 'Data Gagal diubah'.$e->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.admin.penjualan.form');
    }
}
