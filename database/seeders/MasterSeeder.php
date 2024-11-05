<?php

namespace Database\Seeders;

use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $r = new \App\Library\APIAccurate();
        $idmember = UserModel::where('user_type','member')->max('id_accurate') ?? 0;
        $page = 1;
        do {
            $url = '/api/customer/list.do?fields=id,name,customerNo,email,npwpNo,lastUpdate&sp.page=' . $page . '&sp.sort=id|asc&filter.id.op=GREATER_THAN';
            echo $url."\n\m";

            $response = $r->get($url);
            $json = json_decode($response->body(), true);
            $maxpage = (int)$json['sp']['pageCount'];
            $data = $json['d'];
            $bulk = [];
            foreach ($data as $idx=>$v){
                $splname = explode(' ',$v['name']);
                $lastname  = '';
                if(count($splname) > 1) {
                    $lastname = implode(' ', array_slice($splname, 1));
                }
                $bulk = [
                    'id_no' => $v['customerNo'],

                    'first_name' => $splname[0],
                    'last_name' => $lastname,
                    'user_type' => 'member',
                    'email' => $v['email'],
                    'npwp' => $v['npwpNo'],
                    'created_at' => Carbon::now()
                ];
                UserModel::updateOrInsert([
                      'id_accurate' => $v['id'],
                ],$bulk );
            }

            $page++;
            $lanjut = $page <= $maxpage;
            echo "($page < $maxpage = $lanjut) \n";
        }while($lanjut);
    }
}
