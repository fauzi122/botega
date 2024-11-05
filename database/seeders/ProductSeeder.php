<?php

namespace Database\Seeders;

use App\Models\DetailTransactionModel;
use App\Models\ProductModel;
use App\Models\TransactionModel;
use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $r = new \App\Library\APIAccurate();
        $idproduk = ProductModel::whereNotNull('id_accurate')->max('id_accurate') ?? 0;

        $page = 1;
        do {
            $url = '/api/item/list.do?fields=' . urlencode('id,customerNo,transDate,totalAmount,statusName,shipDate,number,charField10,description,approvalStatus') . '&sp.page='.$page.'&sp.sort=id|asc&filter.id.op=GREATER_THAN&filter.id.val[0]='.$idproduk;
            echo "url : $url \n";

            $response = $r->get($url);
          echo $response->body();
            $json = json_decode($response->body(), true);
            $maxpage = (int)$json['sp']['pageCount'];
            $data = $json['d'];
            $bulk = [];
            foreach ($data as $idx=>$v){
                $response2 = $r->get('/api/item/detail.do?id='.$v['id']);
                $json2 = json_decode($response2->body(), true);
                $d = $json2['d'];

                $bulk[] = [
                    'kode' => $d['no'],
                    'name'  => $d['name'],
                    'descriptions'  => $d['charField1'] . ' ' . $d['charField2']  . ' ' . $d['charField3']   ,
                    'price'  => $d['balanceUnitCost'],
                    'id_accurate' => $d['id']
                ];

            }
            ProductModel::insert($bulk);

            $page++;
            $lanjut = $page < $maxpage;
        }while($lanjut);
    }
}
