<?php

namespace App\Jobs;

use App\Library\APIAccurate;
use App\Models\DetailDeliveryOrderModel;
use App\Models\DetailReturPenjualan;
use App\Models\DetailTransactionModel;
use App\Models\FeePaymentMadeModel;
use App\Models\FeeProfessionalModel;
use App\Models\LevelMemberModel;
use App\Models\ProductCategoryModel;
use App\Models\ProductModel;
use App\Models\ProsesHistoryModel;
use App\Models\ReturPenjualanModel;
use App\Models\TransactionModel;
use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PhpOffice\PhpSpreadsheet\Calculation\Category;

class SyncSaleReturnJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $tglTransaksi;
    /**
     * Create a new job instance.
     */
    public function __construct($tglTransaksi = '')
    {
        $this->tglTransaksi = $tglTransaksi == '' ?  Carbon::now()->subMonths(1)->format('d/m/Y') : $tglTransaksi;

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $r = new \App\Library\APIAccurate();
        $transDate = $this->tglTransaksi;
        $page = 1;
        do {
            $url = '/api/sales-return/list.do?fields=' . urlencode('id,number,charField1,approvalStatus') . '&sp.page='.$page.'&filter.transDate.op=GREATER_EQUAL_THAN&filter.transDate.val[0]='.$transDate.'&sp.sort=id|desc';
//            $url = '/api/sales-return/list.do?sp.page='.$page.'&filter.number.op=EQUAL&filter.number.val[0]='.urlencode( 'SOJ/24/01/00183').'&sp.sort=id|desc';
//            echo $url."\n\n";
            $response = $r->get($url);
            $json = json_decode($response->body(), true);
//            var_dump($json);
            try {
                $maxpage = (int)$json['sp']['pageCount'];
                $data = $json['d'];
                $bulk = [];
                foreach ($data as $idx => $v) {
                    if($v['approvalStatus'] == 'APPROVED') {
                        $response2 = $r->get('/api/sales-return/detail.do?id=' . $v['id']);
                        echo $v['id'] . '--';
                        $json2 = json_decode($response2->body(), true);
                        $d = $json2['d'];
                        $id = $this->saveData($d);
                        $this->saveDetail($id, $d['number'],  $d['detailItem']);
                    }
                }
            }catch (\Exception $e){
                echo "error ".$e->getMessage().' '.$e->getLine(). ' ' . $e->getFile();
            }

            $page++;
            $lanjut = $page < $maxpage;
        }while($lanjut);

    }

    private function saveDetail($idretur, $noretur, $detail){

        foreach ($detail as $baris){
            $nomorso = $baris['salesOrder']['number'];
            $productid = $this->getProductID($baris['item']);
            $idaccurate = $baris['id'];

            $data = [
                'retur_id' => $idretur,
                'retur_no' => $noretur,
                'id_accurate' => $idaccurate,
                'product_id' => $productid,
                'so_number' => $nomorso,
                'qty' =>  $baris['quantity'],
                'dpp_amount' =>  $baris['dppAmount'],
                'return_amount' =>  $baris['returnAmount']
            ];

            try {
                $dr = DetailReturPenjualan::query()->where('id_accurate', $idaccurate)->first();

                if ($dr == null) {
                    $data['created_at'] = Carbon::now();
                    DetailReturPenjualan::query()->insertGetId($data);
                } else {
                    $data['updated_At'] = Carbon::now();
                    DetailReturPenjualan::query()->where('id_accurate', $idaccurate)->update($data);
                }

                $this->syncDetailDeliveryOrder($noretur, $baris);

                $trx = TransactionModel::query()->where('nomor_so', $nomorso )->first();
                if($trx != null){
                    $dod =  $baris['deliveryOrderDetail'] ?? [];
                    $unitPrice = $dod['unitPrice'] ?? 0;

                    echo "\nMulai update Trxid : ".$trx->id."\n";
                    echo  'sale_price ' . $unitPrice;
                    if($unitPrice > 0){
                        $kriteria = [
                            'transaction_id' => $trx->id,
                            'product_id' => $productid,
                            'sale_price' => $unitPrice
                        ];
                    }else{
                        $kriteria = [
                            'transaction_id' => $trx->id,
                            'product_id' => $productid,
                        ];
                    }
                    DetailTransactionModel::query()
                        ->where($kriteria)
                        ->update([
                            'retur_no' => $noretur,
                            'retur_qty' => $baris['quantity'],
                        ]);
                    echo "\nUpdate sukses trxid ".$trx->id."\n product :".$productid."\n retur : ".$baris['quantity']." saleprice : ".$baris['deliveryOrderDetail']['unitPrice'];

                }

            }catch (\Exception $e){}
        }
    }

    private function syncDetailDeliveryOrder($returNo, $detail){
        $deliveryOrderID = $detail['deliveryOrderDetailId'];
        echo "\nDelivery order : ".$deliveryOrderID;

        $d = DetailDeliveryOrderModel::query()->where("id_accurate", $deliveryOrderID)->first();
        if($d == null)return;
        $d->retur_qty = $detail['quantityDefault'];
        $d->retur_no = $returNo;
        $d->save();
    }

    private function getProductID($item){
        $id = $item['id'];
        $r = ProductModel::query()->where('id_accurate', $id)->first();
        if($r != null){
            return $r->id;
        }
        $data = [
            'kode' => $item['no'] ?? '',
            'name' => $item['name'] ?? '',
            'descriptions' => ($item['charField1'] ?? '') . ' ' . ($item['charField2'] ?? '') . ' ' . ($item['charField3'] ?? ''),
            'price' => $item['unitPrice'] ?? '',
            'id_accurate' => $item['id'],
            'category_id' => ProductCategoryModel::query()->where('id_accurate', $item['itemCategoryId'])->first()?->id
        ];

        return ProductModel::query()->insertGetId($data);
    }

    private function saveData($d){
        $inv = $d['invoice'] ?? [];
        $data = [
            'id_accurate' => $d['id'],
//            'no_so' => $d['sales_order']['number'],
            'member_user_id' => $this->getCustomer($d),
            'retur_at' =>  Carbon::createFromFormat('d/m/Y', $d['transDate']),
            'no_retur' => $d['number'],
            'number_src' => $inv['number'] ?? '',
            'keterangan' => $d['description'],
            'subtotal' => doubleval($d['subTotal']),
            'returndp' => doubleval($d['returnDownPayment']),

        ];
        $idretur = null;
        try {
            $r = ReturPenjualanModel::query()->where('id_accurate', $d['id'])->first();
            if ($r == null) {
                $data['created_at'] = Carbon::now();
                $idretur = ReturPenjualanModel::query()->insertGetId($data);
            } else {
                $data['updated_at'] = Carbon::now();
                ReturPenjualanModel::query()->where('id_accurate', $d['id'])
                    ->update($data);
                $idretur = ReturPenjualanModel::query()->where('id_accurate', $d['id'])->first()?->id;

            }
        }catch (\Exception $e){
            echo "Keslaahan save : ".$e->getMessage();
        }

//        $this->syncReturToPaymentMade($idretur);

        return $idretur;
    }

    private function syncReturToPaymentMade($idRetur){
        $rp = ReturPenjualanModel::query()->where("id_accurate", $idRetur)->first();
//        echo "Sync returtoPayment rp : ($idRetur) ";
//        var_dump($rp);
        if($rp == null)return;

        $n = ProsesHistoryModel::query()->where('history_number', $rp->number_src)->first();
        echo "\nProseshistory = ".$rp->number_src;
//        var_dump($n);
        if($n == null)return;

        $trx = ProsesHistoryModel::query()->where('transactions_id', $n->transactions_id)->first();
        echo "\nTransaksi id = ".$n->transactions_id;
        if($trx == null)return;

        $feeprop = FeeProfessionalModel::view()->where('transaction_id', $n->transactions_id)->get();
//        echo "Feeprof by trxid = ".$n->transactions_id." \n";
        foreach($feeprop as $k=>$v){
            echo "\nisi feeprop . ";
            if($v->sj_number == '' || $v->invoice_number == ''){
                continue;
            }
//            var_dump($v);
            $n = FeePaymentMadeModel::query()->where([
                'member_user_id' => $v->member_user_id,
                'no_so' => $v->nomor_so,
                'no_sj' => $v->sj_number,
                'no_inv' => $v->invoice_number,
            ])->first();
            echo "fee payment made : ".$v->nomor_so. " " .$v->nomor_sj.' ' .$v->invoice_no;
            if($n == null){
                FeePaymentMadeModel::query()->insertGetId([
                    'member_user_id' => $v->member_user_id,
                    'no_so' => $v->nomor_so,
                    'no_sj' => $v->sj_number,
                    'no_inv' => $v->invoice_number,
                    'nominal' => $v->total_pembayaran,
                    'fee_date' => $v->dt_acc,
                    'keterangan' => 'Fee yang telah terbayarkan namun transaksi telah di retur dengan nomor '.$rp->no_retur. ' sejumlah '.number_format( $rp->returndp)
                ]);
            }else{
                FeePaymentMadeModel::query()->where('id',$n->id)->update([
                    'nominal' => $v->total_pembayaran,
                    'fee_date' => $v->dt_acc,
                    'keterangan' => 'Fee yang telah terbayarkan namun transaksi telah di retur dengan nomor '.$rp->no_retur. ' sejumlah '.number_format( $rp->returndp)
                ]);

            }
        }
    }

    private function getCustomer($data){
        $c = UserModel::query()->where('id_accurate', $data['customerId'])->first();
        if($c == null){
            $customer = $data['customer'];
            $name = explode(' ' , $customer['name']);
            $listaddress = $customer['shipAddressList'];
            $cla = count($listaddress);
            $address = [];
            if($cla > 0){
                $address = $listaddress[ $cla - 1 ];
            }

            $lvlmember = LevelMemberModel::query()->orderBy('level','asc')->first();

            $data= [
                'first_name' => $name[0],
                'last_name' =>  implode(' ', array_slice($name, 1)),
                'id_no' => $customer['customerNo'],
                'user_type' => 'member',
                'level_member_id' => $lvlmember?->id,
                'npwp' => $customer['npwpNo'],
                'home_addr' => $address['address'] ?? '',
                'zip_code' => $address['zipCode'] ?? '',
                'created_at' => Carbon::now()
            ];
            return UserModel::query()->insertGetId($data);
        }else{
            return $c->id;
        }
    }
}
