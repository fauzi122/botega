<?php

namespace App\Livewire\Admin\Fee;

use App\Http\Controllers\Admin\LogController;
use App\Jobs\SyncPenjualanJob;
use App\Library\ValidatedPermission;
use App\Models\ClaimItemTransactionModel;
use App\Models\DetailDeliveryOrderModel;
use App\Models\DetailTransactionModel;
use App\Models\FeeNumberModel;
use App\Models\FeePaymentMadeModel;
use App\Models\FeeProfessionalModel;
use App\Models\FeeSplitModel;
use App\Models\ProsesHistoryModel;
use App\Models\TransactionModel;
use App\Models\UserModel;
use App\Models\UserRekeningModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Form extends Component
{
    public $editform = false;
    public $detail_transactions;
    public $inv_no;
    public $member_user_id;
    public $detail_transaction_id;
    public $fee_professional;
    public $member;
    public $userRekening;
    public $namamemberlengkap;

    public $listsj = [];
    public $nosj;
    public $noso;
    public $customerName;
    public $transaction_id;
    public $selectNoSO;
    public $percentage_fee;

    public $fee_number_nomor;
    public $fee_number_id;

    public $modeData=1;
    public $paymentMade;

    private function validasi(){
        $v = $this->validate([
            'member_user_id' => 'required',
            'transaction_id' => 'required',
            'detail_transaction_id' => 'required',
        ],[
            'member_user_id' => 'Member profesional harus diisikan',
            'transaction_id' => 'Nomor Invoice transaksi harus dipilih',
            'detail_transaction_id' => 'Detail Item transaksi harus dipilih'
        ]);
        return $v;
    }

    public function hapusFee($fee){
        $id = $fee['id'];
        $r = FeeProfessionalModel::query()->where('id', $id)->whereNull('dt_acc')->first();
        if($r == null)return;

        $deleted = false;
        if($r?->detail_delivery_id != null){
            $hassplit = FeeSplitModel::hasSplit($r->member_user_id, $r->detail_transaction_id, null);

            if($hassplit === false) {
                DetailTransactionModel::query()->where('id', $r->detail_transaction_id)->update(['status_claim'=>null]);

                ClaimItemTransactionModel::query()->where([
                    'member_user_id' => $r->member_user_id,
                    'detail_transactions_id' => $r->detail_transaction_id,
                ])->delete();
                FeeSplitModel::query()->where([
                    'member_user_id' => $r->member_user_id,
                    'detail_transaction_id' => $r->detail_transaction_id,
                    'num_split' => 1
                ])->delete();
            }else{
                FeeSplitModel::query()->where([
                    'member_user_id' => $r->member_user_id,
                    'detail_transaction_id' => $r->detail_transaction_id,
                    'num_split' => 2
                ])->update(['fee_professional_id'=>null]);
            }

            $hassplit = FeeSplitModel::hasSplit($r->member_user_id, null, $r->detail_delivery_id);
            if($hassplit === false) {
                DetailDeliveryOrderModel::query()->where('id', $r->detail_delivery_id)->update(['status_claim'=>null]);

                ClaimItemTransactionModel::query()->where([
                    'member_user_id' => $r->member_user_id,
                    'detail_delivery_order_id' => $r->detail_delivery_id,
                ])->delete();
                FeeSplitModel::query()->where([
                    'member_user_id' => $r->member_user_id,
                    'detail_delivery_order_id' => $r->detail_delivery_id,
                    'num_split' => 1
                ])->delete();
                $deleted = true;
            }else{
                FeeSplitModel::query()->where([
                    'member_user_id' => $r->member_user_id,
                    'detail_delivery_order_id' => $r->detail_delivery_id,
                    'num_split' => 2
                ])->update(['fee_professional_id'=>null]);
            }

        }else{
            $hassplit = FeeSplitModel::hasSplit($r->member_user_id,  $r->detail_transaction_id, null);
            if($hassplit === false) {
                DetailTransactionModel::query()->where('id', $r->detail_transaction_id)->update(['status_claim' => null]);
                ClaimItemTransactionModel::query()->where([
                    'member_user_id' => $r->member_user_id,
                    'detail_transactions_id' => $r->detail_transaction_id,
                ])->delete();
                FeeSplitModel::query()->where([
                    'member_user_id' => $r->member_user_id,
                    'detail_transaction_id' => $r->detail_transaction_id,
                    'num_split' => 1
                ])->delete();

            }else{
                FeeSplitModel::query()->where([
                    'member_user_id' => $r->member_user_id,
                    'detail_transaction_id' => $r->detail_transaction_id,
                    'num_split' => 2
                ])->update(['fee_professional_id'=>null]);
            }
        }
        $r->delete();

        FeeNumberModel::rekapNilaiFee($r->fee_number_id);
        $this->dispatch('refresh1');
        LogController::writeLog(ValidatedPermission::HAPUS_DATA_FEE, 'Hapus  fee  ', $id);
    }

    private function saveFeeNumber(){
        $data = [
            'member_user_id' => $this->member_user_id,
            'npwp' => $this->member->npwp,
            'bank' => $this->userRekening?->bank_akro,
            'no_rekening' =>$this->userRekening?->no_rekening,
            'an_rekening' => $this->userRekening?->an,
        ];

        $r = FeeNumberModel::query()
                        ->where('member_user_id', $this->member_user_id)
                        ->whereNull('dt_pengajuan')
                        ->first();
        if($r == null){
            $data['nomor'] = FeeNumberModel::generateNomor();
            $data['created_at'] = Carbon::now();
            return FeeNumberModel::query()->insertGetId($data);
        }else{
            $data['updated_at'] = Carbon::now();
            $r->update($data);
        }
        return $r->id;
    }



    private function checkClaim($id, $type){
        $hassplit = null;

        if($type == 'DD'){

            $ddo = DetailDeliveryOrderModel::query()->where('id',$id)->first();
            $cit = ClaimItemTransactionModel::query()->where([
                'member_user_id' => $this->member_user_id,
                'detail_delivery_order_id' => $id,
                'detail_transactions_id' => $ddo->detail_transaction_id
            ])->first();
//            if($cit == null){
//                $ddo = DetailDeliveryOrderModel::query()->where('id',$id)->first();
//                $cit = ClaimItemTransactionModel::query()->where([
//                    'member_user_id' => $this->member_user_id,
//                    'detail_transactions_id' => $ddo->detail_transaction_id
//                ])->first();
//            }

            if($cit != null){
                $hassplit = FeeSplitModel::hasSplit($this->member_user_id, $cit->detail_transactions_id, $cit->detail_delivery_order_id);
                if($hassplit === false) {
                    session()->flash('error', 'Item transaksi sudah pernah diajukan claim');
                    return false;
                }
            }
        }else{
            $cit = ClaimItemTransactionModel::query()->where([
                'member_user_id' => $this->member_user_id,
                'detail_transactions_id' => $id
            ])->first();
            if($cit != null){
                $hassplit = FeeSplitModel::hasSplit($this->member_user_id, $cit->detail_transactions_id, $cit->detail_delivery_order_id);

                if($hassplit === false) {
                    $r = [$this->member_user_id, $cit->detail_transactions_id, $cit->detail_delivery_order_id];
                    session()->flash('error', 'Item transaksi ini sudah pernah di claim ');
                    return false;
                }
            }

        }

        return $hassplit;
    }

    private function getNoInv_SJ($type, $dt){
        $inv = null;
        $sj = null;
        if($type == 'DD'){
            $inv = ProsesHistoryModel::query()->where('history_number', $dt->number_in)->first();
            $sj = ProsesHistoryModel::query()->where('history_number', $dt->number_sj)->first() ;
        }

        if($inv == null) $inv = $this->getHistoryFromTrx($dt->transaction_id, 'IN');
        if($sj == null) $sj = $this->getHistoryFromTrx($dt->transaction_id, 'SJ');
        return [$inv, $sj];
    }

    private function prepareData($data, $type, $detailTransaction){
        $idDetailDelivery = $type == 'DD' ? $detailTransaction?->id : null;

        [$inv, $sj] = $this->getNoInv_SJ($type, $detailTransaction);

        $feepercent = 3;
        $feeamount =  $detailTransaction->dpp_amount * ($feepercent / 100);
        $feeamountretur = doubleval($feeamount / $detailTransaction->qty) * doubleval( $detailTransaction?->retur_qty ?? 0,);
        $feeamount = $feeamount - $feeamountretur;
        $hasnpwp =  strlen($this->member->npwp ?? '') > 7;
        $pphpercent =  $hasnpwp ? ($this->member->is_perusahaan ? 2 : 2.5) :  ($this->member->is_perusahaan ? 4 : 3);
        $pphamount = $feeamount * ($pphpercent/100);
        $totalbayar = $feeamount - $pphamount;

        $dppamountretur = ($detailTransaction?->dpp_amount /  $detailTransaction?->qty) * ( $detailTransaction?->retur_qty ?? 0 );
        $dpptotal = doubleval( $detailTransaction?->dpp_amount)  -  $dppamountretur;


        $id_feenum = $this->saveFeeNumber();

        $v = array_merge($data, [
            'fee_number_id' => $id_feenum,
            'proses_history_invoice_id' => $inv?->id,
            'invoice_date' => $inv?->history_date ?? $detailTransaction?->trx_at,
            'proses_history_nomor_sj' => $sj?->id,
            'fee_percent' => $feepercent,
            'fee_amount' => $feeamount,
            'npwp' => $this->member->npwp,
            'pqty' => $detailTransaction?->qty,
            'unit' => $detailTransaction?->unit,
            'dpp_amount' => ceil($dpptotal),
            'discount' => $detailTransaction?->discount,
            'total_price' => $detailTransaction?->total_price,
            'pph_percent' => $pphpercent,
            'pph_amount' =>  $pphamount,
            'total_tagihan' => $totalbayar,
            'percentage_fee' => 100,
            'total_pembayaran' => $totalbayar,
            'harus_dibayar' => $totalbayar,
            'created_at' => Carbon::now(),
            'nama_bank' => $this->userRekening?->bank_akro,
            'bank_kota' => $this->userRekening?->bank_kota,
            'no_rekening' =>$this->userRekening?->no_rekening,
            'an_rekening' => $this->userRekening?->an,
            'member_user_id' => $this->member_user_id,
            'detail_transaction_id' => $this->detail_transaction_id,
            'detail_delivery_id' => $idDetailDelivery,
            'num_split' => 1,
            'retur_no' => $detailTransaction?->retur_no ?? null,
            'retur_qty' => $detailTransaction?->retur_qty ?? 0,
        ]);
        $attr = [
            'member_user_id' => $this->member_user_id,
            'detail_transaction_id' => $this->detail_transaction_id,
            'detail_delivery_id' => $idDetailDelivery,
        ];
        return [$attr, $v];
    }

    private function catatLogClaim($type, $id, $idfee, $dt){
        if($type == 'DD'){
            DetailDeliveryOrderModel::query()->where('id', $id)->update([
                'status_claim' => 'DD' . $idfee
            ]);
            DetailTransactionModel::query()->where('id', $dt->detail_transaction_id)->update([
                'status_claim' => 'DD' . $idfee
            ]);
            ClaimItemTransactionModel::query()->updateOrInsert([
                'detail_transactions_id' => $dt->detail_transaction_id,
                'member_user_id' => $this->member_user_id
            ]);
            ClaimItemTransactionModel::query()->updateOrInsert([
                'detail_delivery_order_id' => $id,
                'member_user_id' => $this->member_user_id
            ]);

        }else {
            DetailTransactionModel::query()->where('id', $id)->update([
                'status_claim' => 'X' . $idfee
            ]);
            ClaimItemTransactionModel::query()->updateOrInsert([
                'detail_transactions_id' => $id,
                'member_user_id' => $this->member_user_id
            ]);
        }
    }

    public function addItemForFee($id, $type=''){
        $splitfee = $this->checkClaim($id, $type);
        $ffee = null;

        if($splitfee === false)return false;
        $this->sttsf = 'BB';

        if($type == 'DD'){
            $dt = DetailDeliveryOrderModel::view()->where('id', $id)->first();

            $this->detail_transaction_id = $dt->detail_transaction_id;
        }else{
            $dt = DetailTransactionModel::view()->where('id', $id)->first();
            $this->detail_transaction_id = $id;
        }

        $this->member = UserModel::where('id', $this->member_user_id)->first();
        $this->userRekening = UserRekeningModel::view()->where('user_id', $this->member_user_id)
                            ->orderBy('is_primary', 'desc')->first();


        $v = $this->validasi();
        [$attr, $v] = $this->prepareData($v, $type, $dt);

        if($attr['detail_delivery_id'] == null){
            unset($attr['detail_delivery_id']);
        }
        $tffee = FeeProfessionalModel::query()->where($attr)->orderBy('num_split','asc')->get();

        if($tffee->count() >= 2 ){
            session()->flash('error', "Item tidak bisa ditambahkan lagi");
            return false;
        }else if($tffee->count() <= 0){

        }else if($tffee->count() == 1){

            if($splitfee != null){
                if($splitfee->id->percentage >= 100){
                    session()->flash('error', "Pengajuan item  ini sudah dilakukan.");
                    return false;
                }

                $fpm2 = FeeProfessionalModel::query()->where($attr)
                            ->whereNotNull('dt_acc')
                            ->first();

                if($fpm2 != null){
                    $v['num_split'] = 2;
                    $v['percentage_fee'] = 100 - $splitfee->id->percentage;
                    $v['total_pembayaran'] = $splitfee->id->fee_outstanding;

                }else{
                    session()->flash('error', "Pengajuan item  ini sudah ada sebelumnya dan belum di setujui");
                    return false;
                }
            }else{
                session()->flash('error', "Item tidak memiliki split pembayaran");
                return false;
            }
        }

//        if($ffee == null){
        $v['created_at'] = Carbon::now();
        $idfee = FeeProfessionalModel::query()->insertGetId($v);
        if($v['num_split'] == 2){
            FeeSplitModel::query()->where([
                'member_user_id' => $v['member_user_id'],
                'detail_transaction_id' => $v['detail_transaction_id'],
                'detail_delivery_order_id' => $v['detail_delivery_id'],
            ])->update(['fee_professional_id'=>$idfee]);
        }

        $this->catatLogClaim($type, $id, $idfee, $dt);
        FeeSplitModel::splitFee($idfee);
//        }


        FeeNumberModel::rekapNilaiFee($v['fee_number_id']);
        $this->dispatch('refresh1');

        LogController::writeLog(ValidatedPermission::TAMBAH_DATA_FEE, 'Tambah Fee  ', array_merge($attr, $v));
    }

    public function getInfoTransaction($idtrx){
        $trx = TransactionModel::view()->where('id', $idtrx)->first();
        if($trx == null)return;

        $this->transaction_id = $trx->id;
        $this->nosj = $trx?->nomor_sj ?? '';
        $this->noso = $trx?->nomor_so ?? '';
        $this->customerName = $trx?->member . ' ' . $trx?->last_name;
        $this->listsj = ProsesHistoryModel::query()
                            ->whereRaw('LEFT(history_number,2)=?',['SJ'])
                            ->where('transactions_id', $this->transaction_id)->get();
    }

    private function getHistoryFromTrx($trxId, $prefix){
        return ProsesHistoryModel::query()->where('transactions_id', $trxId)
                ->whereRaw("LEFT(history_number,2) = ?", [$prefix])->first();
    }

    public function changeInvoice($idfee, $newvalue){
        $ph = ProsesHistoryModel::query()->where('id', $newvalue)->first();

        FeeProfessionalModel::query()->where('id', $idfee)->update([
            'proses_history_invoice_id' => $newvalue,
            'invoice_date' => $ph->history_date
        ]);
    }

    public function ubahPercentPaidFee($idfee, $percent){
        $fee = FeeProfessionalModel::view()->find($idfee);
        if($fee == null)return;

        $harusdibayar = $fee->total_tagihan * (doubleval($percent) / 100);
        $fee->total_pembayaran = $harusdibayar;
        $fee->percentage_fee = doubleval($percent);
        $fee->save();
        FeeSplitModel::splitFee($idfee);
    }

    public function ubahFee($idfee, $percent){
        $fee = FeeProfessionalModel::view()->find($idfee);
        if($fee == null)return;
        $detail = DetailTransactionModel::find($fee->detail_transaction_id);
        if($detail == null)return;

        $fee->fee_percent = $percent;
        $fee->fee_amount = $fee->dpp_amount * $percent / 100;
//        $feeretur = ($fee->fee_amount / $fee->pqty) * $fee->retur_qty;
//        $fee->fee_amount = $fee->fee_amount - $feeretur;
        $fee->pph_amount =   $fee->fee_amount * (  $fee->pph_percent / 100);
        $fee->total_tagihan =  $fee->fee_amount  -   $fee->pph_amount ;
        $fee->total_pembayaran = $fee->total_tagihan * (doubleval($fee->percentage_fee) /100);

        $fee->save();

        FeeNumberModel::rekapNilaiFee($fee->fee_number_id);
        $this->dispatch('refresh1');
    }

    public function changeSJ($idfee, $newvalue){
        FeeProfessionalModel::query()->where('id', $idfee)->update([
            'proses_history_nomor_sj' => $newvalue,
        ]);
    }

    public function edit($userid){
        $this->member_user_id = $userid;
    }

    public function newdata(){
        $this->transaction_id = null;
        $this->member_user_id = null;
    }

    public function gantiSJ(){

    }

    public function refreshInfoProduct(){
        SyncPenjualanJob::dispatch('',false,$this->noso)->onConnection('sync');
    }


    public function cekDetailTerproses($id){
        $this->nosj = (int)$this->nosj;
//        if($this->nosj == 0){
            $claim = ClaimItemTransactionModel::query()->where('detail_transactions_id', $id)->get();
            if($claim->isEmpty())return "Tidak ditemukan";
            $feeprof = FeeProfessionalModel::query()->whereIn("member_user_id", $claim->pluck("member_user_id"))
                                ->where("detail_transaction_id", $id)->get();

//        }else{
//            $claim = ClaimItemTransactionModel::query()->where('detail_delivery_order_id', $id)->get();
//            if($claim->isEmpty())return "Tidak ditemukan";
//            $feeprof = FeeProfessionalModel::query()->whereIn("member_user_id", $claim->pluck("member_user_id"))
//                ->where("detail_delivery_id", $id)->get();
//        }
        if($feeprof == null)return "Info Tidak ditemukan";
        $feenum = FeeNumberModel::query()->whereIn('id', $feeprof->pluck('fee_number_id'))->get();

        if($feenum->isEmpty())return "Info Tidak ditemukan";
        $member = UserModel::query()->whereIn("id", $feeprof->pluck("member_user_id"))->get(["id","first_name", "last_name"])->keyBy("id")->toArray();
        $tmp = '';
        foreach($feenum as $fp){
            $user = $member[$fp->member_user_id];
            $tmp .= "{$fp->nomor} : ".$user["first_name"]." ".$user["last_name"]."\n";
        }
        return $tmp;
    }


    public function render()
    {
        $this->nosj = (int)$this->nosj;

        $trxid = (int)$this->transaction_id;

        if($this->nosj == 0) {
            $this->modeData=1;
            $this->detail_transactions = DetailTransactionModel::view()
                                             ->where("transaction_id", $trxid)->get();
        }else{
            $this->modeData = 2;
            $nosj = ProsesHistoryModel::query()->where('id', $this->nosj)->first();
            $this->detail_transactions = DetailDeliveryOrderModel::view()
                                            ->where('number_sj', $nosj->history_number )
                                            ->where("transaction_id", $trxid)->get();

        }

        $this->fee_professional = FeeProfessionalModel::view()
                                    ->whereNull(['dt_acc', 'dt_finish'])
                                    ->where('member_user_id', $this->member_user_id)->get();

        $this->member = UserModel::where('user_type','member')
                                    ->where('id', $this->member_user_id)->first();
        $this->namamemberlengkap = $this->member?->first_name . ' ' . $this->member?->last_name . ' ('.$this->member?->id_no.')';

        $this->paymentMade = FeePaymentMadeModel::query()
                                ->where('member_user_id',$this->member_user_id)
                                ->where('nominal','>',0)
                                ->where('nominal_hutang', '<', DB::raw('nominal'))->get();

        return view('livewire.admin.fee.form');
    }
}
