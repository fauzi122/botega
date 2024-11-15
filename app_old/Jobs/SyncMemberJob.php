<?php

namespace App\Jobs;

use App\Library\APIAccurate;
use App\Models\KategoriMemberModel;
use App\Models\LevelMemberModel;
use App\Models\User;
use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Matrix\Exception;

class SyncMemberJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $mode;// mode =0 all, mode=1 syncfromaccurate, mode=2 synctoaccurate
    /**
     * Create a new job instance.
     */
    public function __construct($mode = 0)
    {
        $this->mode = $mode;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if($this->mode == 0) {
            $this->syncFromAccurate();
            $this->synctoAccurate();
        }else if($this->mode == 1){
            $this->syncFromAccurate();
        }else if($this->mode == 3){
            echo "mode 3";
            $this->syncMemberKategoriNull();
        }else{
            $this->synctoAccurate();
        }
    }

    public function synctoAccurate(){
        $api = new APIAccurate();
        $us = UserModel::where('user_type', 'member')->whereNull('id_accurate')->get();
        $url = '/api/customer/save.do';
        foreach($us as $u){
            $r = $api->post($url, [

                'name' => $u->first_name . ' ' . $u->last_name,
                'transDate' => Carbon::parse($u->created_at)->format('d/m/Y'),
                'email' => $u->email,
                'customerNo' => $u->id_no,
                'npwpNo' => $u->npwp,
                'pkpNo' => $u->nppkp,
                'mobilePhone' => $u->hp,
                'fax' => $u->fax,
                'website' => $u->web,
                'workPhone' => $u->phone,
                'shipZipCode' => $u->zip_code,
                'shipStreet' => $u->home_addr,
                'shipCountry' => $u->country,
            ]);
            if($r->status() == 200) {
                $result = json_decode($r->body(), true);
                var_dump($result);
                try {
                    $r = $result['r'] ?? [];
                    UserModel::where('id', $u->id)->update(['id_accurate'=> $r['id']]);
                }catch (\Exception $e){
                    echo $e->getMessage();
                }
            }
        }
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

    public function syncMemberKategoriNull(){
        $r = UserModel::query()->whereNull("kategori_id")->get();
        echo "data : ".$r->count()."\n";
        $api = new APIAccurate();
        try {
            foreach ($r as $u) {
                echo "cus no : ".$u->id_no." ID ".$u->id." | idaccurate : ".$u->id_accurate."\n";
                $url = '/api/customer/list.do?fields=id,name,customerNo,category,email,npwpNo,lastUpdate&filter.keywords.op=EQUAL&filter.keywords.val[0]=' . urlencode($u->id_no) . '&sp.sort=id|desc';
                $hasil = $api->get($url);
                $json = json_decode($hasil->body(), true);
                $data = $json['d'];
                if (count($data) > 0) {
                    $category = $data[0]['category'];
                    $data = [
                        'kategori_id' => $category['id']
                    ];
                    echo "data " . json_encode($data) . " id = " . $u->id . "\n";
                    UserModel::query()->where("id", $u->id)->update($data);
                }else{
                    echo json_encode($data);
                }
            }
        }catch (Exception $e){
            echo "error ".$e->getMessage();
        }
    }


    public function syncFromAccurate(){
        $r = new APIAccurate();
        $idmember = UserModel::where('user_type','member')->max('id_accurate');
        $page = 1;
        $lvlmemberid = LevelMemberModel::query()->orderBy('level','asc')->first();

        do {
            $url = '/api/customer/list.do?fields=id,name,customerNo,category,email,npwpNo,lastUpdate&sp.page=' . $page . '&sp.sort=id|desc';
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
                    'level_member_id' => $lvlmemberid?->id,
                    'email' => $v['email'],
                    'npwp' => $v['npwpNo'],

                    "kategori_id" => $this->getKategoriMember($v['category']['id']),
                    'created_at' => Carbon::now()
                ];
                if($bulk['id_no'] == 'VA.24041') {
//                    echo $bulk['id_no'] . " = " . $bulk['first_name'] . " " . $v['id'] . " kategori: " . $bulk['kategori_id'] . "\n";
                }
                $exist = UserModel::query()->where('id_accurate', $v['id'])->first();
                if($exist) {
//                    var_dump($bulk);
                    UserModel::query()->where('id_accurate', $v['id'])->update($bulk);
                }else{
                    $bulk['id_accurate'] = $v['id'];
                    $exist = User::query()->where('id_no', $v['customerNo'])->first();
                    if($exist){
                        UserModel::query()->where('id_no', $exist->id_no)->update($bulk);
                    }else {
                        UserModel::query()->insert($bulk);
                    }
                }

            }

            $page++;
            $lanjut = $page <= $maxpage;
            echo "($page < $maxpage = $lanjut) \n";
        }while($lanjut);
    }
}
