<?php

namespace App\Jobs;

use App\Library\APIAccurate;
use App\Models\CatatanPrivateModel;
use App\Models\DetailDeliveryOrderModel;
use App\Models\DetailTransactionModel;
use App\Models\KategoriMemberModel;
use App\Models\LevelMemberModel;
use App\Models\ProductCategoryModel;
use App\Models\ProductModel;
use App\Models\ProsesHistoryModel;
use App\Models\TransactionModel;
use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Matrix\Exception;

class SyncPenjualanJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $tglTransaksi;
    private $updateUnProcessed = true;
    private $nomorSO;
    private $api;
    private $tgl2;
    private $idtrx;

    /**
     * Create a new job instance.
     */
    public function __construct($tglTransaksi = '', $updateUnprocessed = true, $nomorSO = '', $tgl2=null)
    {
            $this->nomorSO = $nomorSO;
            $this->updateUnProcessed = $updateUnprocessed;
            $this->tglTransaksi =  $tglTransaksi;
            $this->api = new APIAccurate();
            $this->tgl2 = $tgl2;
            $this->idtrx = CatatanPrivateModel::query()->insertGetId([
                'catatan' => "Memulai syncpenjualanjob $tglTransaksi, $updateUnprocessed, $nomorSO, $tgl2",
                'created_at' => Carbon::now()
            ]);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if($this->nomorSO != ''){
            $this->getInfoProductBySO($this->nomorSO);
            return;
        }

        if($this->tglTransaksi == '') {
            $this->getFillProductID();
        }else{
            $this->getNewData();
        }

        if ($this->updateUnProcessed) {
            $this->getUpdateData();
        }

        $r = CatatanPrivateModel::query()->find($this->idtrx);
        if($r != null){
            $r->updated_at = Carbon::now();
            $r->save();
        }
    }

    private function getNewData(){
        $r = $this->api;
        $transDate = $this->tglTransaksi;

        $page = 1;
        $maxpage = 0;
        $starttime = time();

        $detail = '';
        do {
            if($this->tgl2!=null){
                $url = '/api/sales-order/list.do?fields=' . urlencode('id,customerNo,transDate').'&sp.page='.$page.'&filter.transDate.op=BETWEEN&filter.transDate.val[0]='.urlencode($this->tglTransaksi).'&filter.transDate.val[1]='.urlencode($this->tgl2).'&sp.sort=transDate|asc';
//
            }else {
                $url = '/api/sales-order/list.do?fields=' . urlencode('id,customerNo,transDate') . '&sp.page=' . $page . '&filter.transDate.op=GREATER_EQUAL_THAN&filter.transDate.val[0]=' . $transDate . '&sp.sort=transDate|asc';
            }
//            $url = '/api/sales-order/list.do?sp.page='.$page.'&filter.number.op=EQUAL&filter.number.val[0]='.urlencode( 'SOJ/24/01/00259').'&sp.sort=id|desc';
//            echo $url."\n\n";
            $response = $r->get($url);
            if($response == ""){}
            else {
                $detail .= "Url : $url \n";
                $json = json_decode($response->body(), true);
//            var_dump($json);
                try {
                    $maxpage = (int)$json['sp']['pageCount'];
                    $data = $json['d'];
                    $bulk = [];
                    $nomor = 0;
                    $nogagal = 0;
                    foreach ($data as $idx => $v) {
                        $detail .= "Response : ".$v['id']." -- \n";
                        $response2 = $r->get('/api/sales-order/detail.do?id=' . $v['id']);
                        if($response2 == ''){
                            $nogagal++;
                        }else {
//                    echo $v['id'] . '--';
                            $json2 = json_decode($response2->body(), true);
                            $d = $json2['d'];
                            $this->setupSaving($d);
                            $nomor++;
                        }
                    }
                    $detail .= "Total $nomor response berhasil dan $nogagal Gagal";
                } catch (\Exception $e) {
                }
            }

            $page++;
            $lanjut = $page <= $maxpage;
        }while($lanjut);
        $endtime = time();
        $lama = $endtime - $starttime;

        CatatanPrivateModel::query()->insertGetId([
            'catatan' => "Proses getNewData selesai dilakukan selama $lama detik sebanyak $maxpage halaman. \n $detail",
            'created_at' => Carbon::now()
        ]);
    }

    private function getFillProductID(){
        $starttime = time();
        $ra = DetailTransactionModel::getSOProductNull()->get();
        $maxpage = 0;
        foreach($ra as $dtm) {
            $this->getInfoProductBySO($dtm->nomor_so);
            $maxpage++;
        }
        $endtime = time();
        $lama =  $endtime - $starttime;

        CatatanPrivateModel::query()->insertGetId([
            'catatan' => "Proses getFillProductID selesai dilakukan selama $lama detik sebanyak $maxpage",
            'created_at' => Carbon::now()
        ]);
    }

    private function getInfoProductBySO($nomorSO, $page = 1, $attempt = 0){
        $url = '/api/sales-order/list.do?sp.page='.$page.'&filter.number.op=EQUAL&filter.number.val[0]='.urlencode( $nomorSO ).'&sp.sort=id|desc';
//        echo $url."\n\n";

        $response = $this->api->get($url);
        if($response->status() != 200){
            if($attempt < 3){
                sleep(2);
                return $this->getInfoProductBySO($nomorSO, $page, $attempt+1);
            }
        }

        $json = json_decode($response->body(), true);
        $starttime = time();
        $maxpage = 0;
        try {
            $data = $json['d'];
            $bulk = [];
            foreach ($data as $idx => $v) {

                $response2 = $this->api->get('/api/sales-order/detail.do?id=' . $v['id']);
//                echo $v['id'] . '--';
                $json2 = json_decode($response2->body(), true);
                $d = $json2['d'];
                $this->setupSaving($d);
                $maxpage++;
            }
        }catch (\Exception $e){}
        $endtime = time();
        $lama = $endtime-$starttime;
        CatatanPrivateModel::query()->insertGetId([
            'catatan' => "Proses getInfoProductBySO $nomorSO selesai dilakukan selama $lama detik sebanyak $maxpage",
            'created_at' => Carbon::now()
        ]);

    }

    private function getUpdateData(){
        $starttime = time();
        $r = new \App\Library\APIAccurate();
        $data = TransactionModel::whereNotIn('status', ['CLOSED','PROCEED'])->get();
        $maxpage = 0;
        foreach ($data as $v){
            $url = '/api/sales-order/detail.do?id='.$v->id_accurate;
//            echo $url."\n";
            $response2 = $r->get($url);

            $json2 = json_decode($response2->body(), true);
            $d = $json2['d'] ?? [];
            $this->setupSaving($d);
            $maxpage++;
        }
        $endtime = time();
        $lama =   $endtime-$starttime;
        CatatanPrivateModel::query()->insertGetId([
            'catatan' => "Proses getUpdateData selesai dilakukan selama $lama detik sebanyak $maxpage",
            'created_at' => Carbon::now()
        ]);

    }

    private function setupSaving($d){
        trY {
            $id = $this->saveTransaction($d);

            $detail = $d['detailItem'];
            $this->saveDetailItem($id , $detail, $d);

            $proseshistory = $d['processHistory'];
            $this->saveDetailProsesHistory($id, $proseshistory);

            $this->saveDetailDeliveryOrder($id, $proseshistory);

        }catch (\Exception $exception){
            $id = $d['id'] ?? '';
            echo "error " . $exception->getMessage() . ' ' . $exception->getFile() . ' ' .$exception->getLine() . " id = $id \n\n";
        }
    }

    private function getInvoiceNumber($ph){
        foreach($ph as $p){
            $num = $p['historyNumber'];
            if( strtoupper( substr($num, 0,2) ) == 'IN'){
                return $p;
            }
        }
        return null;
    }
    private function saveDetailDeliveryOrder($idTransaction, $prosesHistory){
        $r = new \App\Library\APIAccurate();

        foreach ($prosesHistory as $ph){
            try {
                $number = $ph['historyNumber'];
                if(strtoupper( substr($number,0,2)) == 'SJ'){
                    $idsj = $ph['id'];
                    $url = '/api/delivery-order/detail.do?id='.$idsj;
//                    echo $url."\n";
                    $response = $r->get($url);
                    if($response == ''){}else {
                        $json2 = json_decode($response->body(), true);
                        $d = $json2['d'];
                        $processHist = $d['processHistory'];
                        $lph = $this->getInvoiceNumber($processHist);
                        $noin = null;
                        if ($lph != null) {
                            $noin = $lph['historyNumber'];
                        }
                        $detailitems = $d['detailItem'];

                        foreach ($detailitems as $detailitem) {
                            $item = $detailitem['item'];
                            $so = $detailitem['salesOrder'];
                            $idproduk = $item['id'];

                            $salesOrderDetailId = $detailitem['salesOrderDetailId'];
                            $detailTransaction = DetailTransactionModel::query()->where('id_accurate', $salesOrderDetailId)->first();
                            if ($detailTransaction == null) {
                                $appamountunit = 0;
                            } else {
                                $appamountunit = $detailTransaction?->dpp_amount / $detailTransaction?->qty;
                            }

                            $data = [
                                'detail_transaction_id' => $detailTransaction?->id,
                                'number_sj' => $d['number'],
                                'number_so' => $so['number'],
                                'number_in' => $noin,
                                'process_qty' => $detailitem['quantity'],
                                'unit' => $detailitem['itemUnit']['name'],
                                'dpp_amount_unit' => $appamountunit,
                                'id_accurate' => $detailitem['id'],
                                'proses_history_id' => ProsesHistoryModel::query()->where('id_accurate', $idsj)->first()?->id,
                            ];

                            $deliveryOrder = DetailDeliveryOrderModel::query()->where('id_accurate', $detailitem['id'])->first();
                            if ($deliveryOrder == null) {
                                $data['created_at'] = Carbon::now();
                                DetailDeliveryOrderModel::query()->insertGetId($data);
                            } else {
                                $data['updated_at'] = Carbon::now();
                                DetailDeliveryOrderModel::query()->where('id_accurate', $detailitem['id'])->update($data);
                            }

                        }
                    }

                }
            }catch (\Exception $e){
//                echo "Error updateSaveDetailProsesHistory : ".$e->getMessage().'--'.$e->getLine().':'.$e->getFile().'\n';
            }
        }
    }

    private function getMember($customer){
        $splname = explode(' ',$customer['name']);
        $lastname  = '';
        if(count($splname) > 1) {
            $lastname = implode(' ', array_slice($splname, 1));
        }
        $listaddress = $customer['shipAddressList'];
        $cla = count($listaddress);
        if($cla > 0){
            $address = $listaddress[ $cla - 1 ];
        }

        $lvlmember = LevelMemberModel::query()->orderBy('level','asc')->first();

        try {

            $data =  [
                'id_no' => $customer['customerNo'],
                'first_name' => $splname[0],
                'last_name' => $lastname,
                'user_type' => 'member',
                'level_member_id' => $lvlmember?->id,
                'npwp' => $customer['npwpNo'],
                'home_addr' => $address['address'] ?? '',
                'zip_code' => $address['zipCode'] ?? '',
                'created_at' => Carbon::now(),
                'id_accurate' => $customer['id'],
                "kategori_id" => $this->getKategoriMember($customer['categoryId']),
            ];

            $user = UserModel::query()->where('id_accurate', $customer['id'])->first();
            if($user == null){
                try {
                    UserModel::query()->insertGetId($data);
                }catch (\Exception $e){
                    $data['updated_at'] = Carbon::now();
                    $data['id_accurate'] = $customer['id'];
                    UserModel::query()->where("id_no", $customer['customerNo'])->update($data);
                }
            }else{
                $data['updated_at'] = Carbon::now();
                UserModel::query()->where("id", $user->id)->update($data);
            }

        }catch (\Exception $e){

        }

        return UserModel::where('id_accurate', $customer['id'])->first();

    }

    private function getKategoriMember($categoryID){
        try {
            $r = KategoriMemberModel::query()->where("id_accurate", $categoryID)->first();
            $api = new \App\Library\APIAccurate();

            $response = $api->get("/api/customer-category/detail.do?id=" . $categoryID);

            if ($response?->status() != 200) {
                return null;
            }

            $json2 = json_decode($response->body(), true);

            $category = $json2['d'];
            $data = [
                "id_accurate" => $categoryID,
                "name" => $category['name'],
            ];
            if ($r == null) {
                $data['created_at'] = Carbon::now();
                return KategoriMemberModel::query()->insertGetId($data);
            } else {
                $data['updated_at'] = Carbon::now();
                KategoriMemberModel::query()->where("id", $r->id)->update($data);
                return $r->id;
            }
        }catch (Exception $e){
            print("error ".$e->getMessage());
        }
        return null;
    }

    private function saveTransaction($d){

        $r = TransactionModel::where('id_accurate', $d['id'])->first();
        $taxable = $d['taxable'] ?? true;
        $dppAmount = $d['dppAmount'] ?? 0;
        if( $taxable == false){

            $dppAmount = $d['salesAmount'];
        }

        $data = [
            'trx_at' => Carbon::createFromFormat('d/m/Y', $d['transDate']),
            'nomor_so' => $d['number'],
            'invoice_no' => $this->getNomorHistory('IN', $d['processHistory']),
            'tgl_invoice' => $this->getTanggalHistory('IN', $d['processHistory']),
            'member_user_id' => $this->getMember($d['customer'])?->id,
            'total' => $d['totalAmount'],
            'dpp_amount' => $dppAmount,
            'nomor_sj' => $this->getNomorHistory('SJ', $d['processHistory']),
            'notes' => $d['description'],
            'has_npwp' => $d['hasNPWP'],
            'id_accurate' => $d['id'],
            'status' => $d['status']
        ];

        if($r == null) {
            $data['created_at'] = Carbon::now();
            return TransactionModel::insertGetId($data);
        }else{
            $data['updated_at'] = Carbon::now();
            TransactionModel::where('id_accurate', $d['id'])->update($data);

            return $r->id;
        }
    }

    private function saveDetailProsesHistory($id, $proseshistory){

        foreach ($proseshistory as $ph){
           try {
               ProsesHistoryModel::query()->updateOrInsert([
                   'id_accurate' => $ph['id'],
               ], [
                   'approval_status' => $ph['approvalStatus'],
                   'history_date' => Carbon::createFromFormat("d/m/Y", $ph['historyDate']),
                   'history_number' => $ph['historyNumber'] ?? '',
                   'history_type' => $ph['historyType'] ?? '',
                   'history_amount' => $ph['historyAmount'] ?? 0,
                   'transactions_id' => $id,
               ]);
           }catch (\Exception $e){
//               echo "Error updateSaveDetailProsesHistory : ".$e->getMessage().'\n';
           }
        }

    }

    private function saveDetailItem($id, $detail, $d){
//        echo "simpan detail";
        $tmpdetail = [];
        foreach ($detail as  $dt) {
            $item = $dt['item'] ?? [];
            $tax = $dt['tax1'] ?? [];
            $itemUnit = $dt['itemUnit'] ?? [];
            try {
//                $product = ProductModel::where('id_accurate', $item['id'] )->first();
//                $product_id = $product?->id;
//                if($product == null){
                    $product_id = $this->addProduct($dt, $item);
//                }

                $idaccurate = $dt['id'];
                $dtm = DetailTransactionModel::query()->where('id_accurate', $idaccurate )->first();
//var_dump($d);
//                echo "\nsimpan detail_transaction : ".$idaccurate;
                $taxable = (int)( $d['taxable'] ?? true );
//                echo "nilai taxable " . $taxable . "\n branch :";
//                echo $d['branchId'];
//                echo " \n dppamount " . $dt['dppAmount'];
                $dppAmount = $dt['dppAmount'] ?? 0;
                if( $taxable == 0){

                    $itemDiscPercent = floatval($dt['itemDiscPercent'] ?? 0);
                    $grossAmount = floatval($dt['salesAmount'] ?? 0);
//                    $disc = $itemDiscPercent / 100 * $grossAmount;
                    echo "dppamount_taxable = 0 ".$grossAmount;
                    $dppAmount = $grossAmount;// - $disc;
                }

                $datanew = [
                    'id_accurate' => $idaccurate,
                    'transaction_id' => $id,
                    'product_id' => $product_id,
                    'qty' => $dt['quantity'] ?? 0,
                    'unit' => $itemUnit['name'] ?? '',
                    'notes' => $dt['detailName'] ?? '',
                    'sale_price' => $dt['unitPrice'] ?? 0,
                    'item_disc_percent' => $dt['itemDiscPercent'] ?? '',
                    'total_price' => $dt['totalPrice'] ?? 0,
                    'discount' => $dt['availableItemCashDiscount'] ?? 0,
                    'dpp_amount' => $dppAmount,
                    'ppn' => $d['tax1Rate'] ?? 0,
                    'salesname' => $dt['salesmanName'],
                ];
                $tmpdetail[] = $idaccurate;

                if($dtm == null){
                    $datanew['created_at'] = Carbon::now();
                    $iddetail = DetailTransactionModel::query()->insertGetId($datanew);
                }else{
                    $datanew['updated_at'] = Carbon::now();
                    DetailTransactionModel::query()->where('id_accurate', $idaccurate )
                            ->update($datanew);
                    $iddetail = $dtm->id;
                }

            }catch (\Exception $e){
//                echo "error SaveDetailItem : ".$e->getMessage().' line : ' . $e->getLine() . ' \n';
            }
        }
        $r = DetailTransactionModel::query()->where('transaction_id', $id)
            ->whereNotIn('id_accurate', $tmpdetail)->delete();

    }

    private function addProduct($item, $product){
        $api = new \App\Library\APIAccurate();
        $response = $api->get('/api/item/detail.do?id='.$product['id'] );
        if($response == ''){}
        else {

            $category = $response->json('d')['itemCategory'];

            $data = [
                'kode' => $product['no'] ?? '',
                'name' => $product['name'] ?? '',
                'descriptions' => ($product['charField1'] ?? '') . ' ' . ($product['charField2'] ?? '') . ' ' . ($product['charField3'] ?? ''),
                'price' => $item['unitPrice'] ?? '',
                'id_accurate' => $product['id'],
                'category_id' => $this->getCategoryProduct($category)
            ];
//        echo $data['kode'] . ' = ' . $data['name'] . '\n';
            $mproduct = ProductModel::query()->where('id_accurate', $product['id'])->first();

            if ($mproduct == null) {
                $data['created_at'] = Carbon::now();
                $rp = ProductModel::query()->insertGetId($data);
//            session()->flash("error", "simpan ".json_encode($data) . ' = ' . $rp);
                return $rp;
            } else {
                $data['updated_at'] = Carbon::now();
                $rp = ProductModel::query()->where('id_accurate', $product['id'])
                    ->update($data);
//            session()->flash("error", "update ".$product['id']. ' -- '.json_encode($data) . ' = ' . $rp);
                return $mproduct->id;
            }
        }

    }

    private function getCategoryProduct($category){
        $id = $category['id'];
        $c = ProductCategoryModel::query()->where('id_accurate', $id)->first();
//        echo "category id_accurate $id \n";
        $data = [
            'category' => $category['name'],
            'id_accurate' => $category['id']
        ];

        if($c == null){
            $data['created_at'] = Carbon::now();
            return ProductCategoryModel::query()->insertGetId($data);
        }else{
            $data['updated_at'] = Carbon::now();
            ProductCategoryModel::query()->where('id_accurate', $id)->update($data);
        }
        return $c->id;
    }

    private function getNomorHistory($prefix, $processHist){
        $le = strlen($prefix);
        $p = strtoupper($prefix);
        foreach ($processHist as $item) {
            $hisNum = $item['historyNumber'];
            $nu = strtoupper(substr($hisNum,0,$le));
            if($nu == $p){
                return $item['historyNumber'];
            }
        }
        return '';
    }

    private function getTanggalHistory($prefix, $processHist){
        $le = strlen($prefix);
        $p = strtoupper($prefix);
        foreach ($processHist as $item) {
            $hisNum = $item['historyNumber'];
            $nu = strtoupper(substr($hisNum,0,$le));
            if($nu == $p){
                return Carbon::createFromFormat("d/m/Y", $item['historyDate'])->format('Y-m-d');
            }
        }
        return null;
    }


}
