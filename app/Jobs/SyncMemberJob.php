<?php

namespace App\Jobs;

use App\Library\APIAccurate;
use App\Models\KategoriMemberModel;
use App\Models\LevelMemberModel;
use App\Models\UserRekeningModel;
use App\Models\UserModel;
use App\Models\BankModel;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Matrix\Exception;
use Illuminate\Support\Facades\Log;

class SyncMemberJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $mode; // mode =0 all, mode=1 syncfromaccurate, mode=2 synctoaccurate
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
        if ($this->mode == 0) {
            $this->syncFromAccurate();
            $this->synctoAccurate();
        } else if ($this->mode == 1) {
            $this->syncFromAccurate();
        } else if ($this->mode == 3) {
            echo "mode 3";
            $this->syncMemberKategoriNull();
        } else {
            $this->synctoAccurate();
        }
    }

    public function synctoAccurate()
    {
        $api = new APIAccurate();
        $us = UserModel::where('user_type', 'member')->whereNull('id_accurate')->get();
        $url = '/api/customer/save.do';
        foreach ($us as $u) {
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
            if ($r->status() == 200) {
                $result = json_decode($r->body(), true);
                var_dump($result);
                try {
                    $r = $result['r'] ?? [];
                    UserModel::where('id', $u->id)->update(['id_accurate' => $r['id']]);
                } catch (\Exception $e) {
                    echo $e->getMessage();
                }
            }
        }
    }

    private function getKategoriMember($categoryID)
    {
        try {
            $r = KategoriMemberModel::query()->where("id_accurate", $categoryID)->first();

            $api = new \App\Library\APIAccurate();

            $response = $api->get("/api/customer-category/detail.do?id=" . $categoryID);
            if ($response?->status() != 200) {
                return null;
            }

            $json2 = json_decode($response->body(), true);

            $category = $json2['d'];
            // return $category;
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
        } catch (Exception $e) {
            print("error " . $e->getMessage());
        }
        return null;
    }

    public function syncMemberKategoriNull()
    {
        $api = new APIAccurate();

        UserModel::query()->whereNull("kategori_id")->chunk(100, function ($users) use ($api) {
            foreach ($users as $u) {
                try {
                    if (empty($u->id_no)) {
                        Log::warning("User ID {$u->id} has no id_no.");
                        continue;
                    }

                    echo "cus no : " . $u->id_no . " ID " . $u->id . " | idaccurate : " . $u->id_accurate . "\n";

                    $url = '/api/customer/list.do?fields=id,name,customerNo,category,email,npwpNo,lastUpdate&filter.keywords.op=EQUAL&filter.keywords.val[0]=' . urlencode($u->id_no) . '&sp.sort=id|desc';
                    $hasil = $api->get($url);
                    $json = json_decode($hasil->body(), true);
                    $data = $json['d'] ?? [];

                    if (isset($data[0]['category']) && isset($data[0]['category']['id'])) {
                        $category = $data[0]['category'];
                        $dataToUpdate = ['kategori_id' => $category['id']];
                        UserModel::query()->where("id", $u->id)->update($dataToUpdate);
                        echo "Updated kategori_id for user ID: " . $u->id . "\n";
                    } else {
                        echo "No category data found for user ID: " . $u->id . "\n";
                        Log::warning("No category data found for user ID: " . $u->id);
                    }
                } catch (Exception $e) {
                    Log::error("Error syncing kategori_id for user ID {$u->id}: " . $e->getMessage());
                    echo "Error: " . $e->getMessage() . "\n";
                }
            }
        });

        echo "Sync completed.\n";
    }



    public function syncFromAccurate()
    {
        $r = new APIAccurate();
        // $idmember = UserModel::where('user_type', 'member')->max('id_accurate');
        $page = 1;
        $lvlmemberid = LevelMemberModel::query()->orderBy('level', 'asc')->first();

        $existingAccurateIds = UserModel::pluck('id_accurate')->toArray();
        $existingCustomerNos = UserModel::pluck('id_no')->toArray();

        do {
            try {
                $url = '/api/customer/list.do?fields=id,name,customerNo,category,email,npwpNo,billStreet,workPhone,mobilePhone,dateField1,charField6,charField7,charField3,charField4,lastUpdate&sp.page=' . $page . '&sp.sort=id|desc';
                Log::info("Fetching data from API: $url");

                $response = $r->get($url);
                $json = json_decode($response->body(), true);
                $maxpage = (int)$json['sp']['pageCount'];
                $data = $json['d'];
            } catch (\Exception $e) {
                Log::error("Error fetching data from API: " . $e->getMessage());
                break;
            }

            foreach ($data as $v) {
                $splname = explode(' ', $v['name'] ?? '');
                $lastname = count($splname) > 1 ? implode(' ', array_slice($splname, 1)) : '';

                $birthAt = isset($v['dateField1']) ? Carbon::createFromFormat('d/m/Y', $v['dateField1'])->format('Y-m-d') : null;

                $existingUser = UserModel::query()->where('id_accurate', $v['id'])->first();

                // Get kategori_id using getKategoriMember
                $kategoriId = $this->getKategoriMember($v['category']['id'] ?? null);

                $bulk = [
                    'id_no' => $v['customerNo'] ?? $existingUser?->id_no ?? '',
                    'first_name' => $splname[0] ?? $existingUser?->first_name ?? '',
                    'last_name' => $lastname ?: $existingUser?->last_name ?? '',
                    'user_type' => 'member',
                    'level_member_id' => $lvlmemberid?->id,
                    'email' => $v['email'] ?? $existingUser?->email ?? '',
                    'npwp' => $v['npwpNo'] ?? $existingUser?->npwp ?? '',
                    'home_addr' => str_replace("\n", " ", $v['billStreet'] ?? $existingUser?->home_addr ?? ''),
                    'phone' => $v['workPhone'] ?? $existingUser?->phone ?? '',
                    'hp' => isset($v['mobilePhone']) && preg_match('/^[0-9+\-\s]+$/', $v['mobilePhone'])
                        ? $v['mobilePhone']
                        : $existingUser?->hp ?? '',
                    'birth_at' => $birthAt ?? $existingUser?->birth_at,
                    'kategori_id' => $kategoriId,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];

                if (in_array($v['id'], $existingAccurateIds)) {
                    UserModel::query()->where('id_accurate', $v['id'])->update($bulk);
                } elseif (in_array($v['customerNo'], $existingCustomerNos)) {
                    UserModel::query()->where('id_no', $v['customerNo'])->update($bulk);
                } else {
                    $bulk['id_accurate'] = $v['id'];
                    UserModel::query()->insert($bulk);
                }

                $an = $v['charField6'] ?? null;
                $bank_kota = $v['charField7'] ?? null;
                $bankName = $v['charField3'] ?? null;
                $accountNumber = $v['charField4'] ??  null;

                $userId = $existingUser?->id;

                if ($userId) {
                    if ($bankName && $accountNumber) {
                        $bank = BankModel::query()->where('akronim', $bankName)->select('id')->first();

                        if ($bank) {
                            UserRekeningModel::query()->updateOrInsert(
                                [
                                    'user_id' => $userId,
                                    'no_rekening' => $accountNumber,
                                ],
                                [
                                    'bank_id' => $bank->id,
                                    'an' => $an,
                                    'bank_kota' => $bank_kota,
                                    'updated_at' => Carbon::now(),
                                    'created_at' => Carbon::now(),
                                ]
                            );

                            Log::info("Data bank untuk user dengan ID Accurate {$v['id']} berhasil disimpan atau diperbarui.");
                        } else {
                            Log::error("Bank dengan akronim '{$bankName}' tidak ditemukan.");
                        }
                    } else {
                        Log::error("Data bank untuk user dengan ID Accurate {$v['id']} tidak tersedia di Accurate.");
                    }
                } else {
                    Log::error("User dengan ID Accurate {$v['id']} tidak ditemukan di tabel `users`. Tidak dapat menyimpan data rekening.");
                }
            }

            $page++;
        } while ($page <= $maxpage);

        Log::info("Sync completed");
    }




    public function syncMemberById($id)
    {
        $api = new APIAccurate();
        $user = UserModel::query()->where('id', $id)->first();

        if (!$user) {
            echo "User dengan ID {$id} tidak ditemukan.\n";
            return;
        }

        try {
            // Mengambil data dari Accurate berdasarkan ID pelanggan (customerNo)
            $url = '/api/customer/detail.do?customerNo=' . urlencode($user->id_no);
            $hasil = $api->get($url);
            $json = json_decode($hasil->body(), true);

            // Validasi apakah JSON memiliki key 'd' dan apakah bukan null
            if (!isset($json['d']) || $json['d'] === null) {
                echo "Data Accurate untuk ID pelanggan {$user->id_no} tidak ditemukan.\n";
                return;
            }

            $accurateData = $json['d']; // Langsung mengambil objek karena JSON hanya memiliki satu elemen

            // Dapatkan atau perbarui kategori menggunakan fungsi getKategoriMember
            $kategori = $this->getKategoriMember($accurateData['category']['id'] ?? null);
            $kategoriId = is_numeric($kategori) ? (int)$kategori : null; // Validasi sebagai integer
            $isNpwpUpdated = isset($accurateData['npwpNo']) && !empty($accurateData['npwpNo']);
            // Update data user
            UserModel::query()
                ->where('id', $id)
                ->update([
                    'first_name' => explode(' ', $accurateData['name'])[0],
                    'last_name' => implode(' ', array_slice(explode(' ', $accurateData['name']), 1)),
                    'email' => $accurateData['email'] ?? $user->email, // Gunakan default jika email tidak ditemukan
                    'npwp' => $accurateData['npwpNo'] ?? $user->npwp,
                    'home_addr' => str_replace("\n", " ", $accurateData['billStreet'] ?? $user->home_addr),
                    'phone' => $accurateData['workPhone'] ?? $user->phone, // Tidak ada data phone di JSON
                    'hp' => $accurateData['mobilePhone'] ?? $user->hp, // Tidak ada data mobile phone di JSON
                    'birth_at' => isset($accurateData['dateField1'])
                        ? Carbon::createFromFormat('d/m/Y', $accurateData['dateField1'])->format('Y-m-d')
                        : $user->birth_at,

                    'kategori_id' => $kategoriId, // Gunakan ID untuk kategori yang diperoleh
                    'updated_at' => now(),
                ]);
            if ($isNpwpUpdated) {
                $user->refresh(); // Refresh user untuk mendapatkan data terbaru dari database
                $user->updateFeeRelatedCalculations();
            }

            // Menyimpan atau memperbarui data bank
            $an = $accurateData['charField6'] ?? null; // Atas Nama
            $bank_kota = $accurateData['charField7'] ?? null; // Bank Kota
            $bankName = $accurateData['charField3'] ?? null; // Nama bank
            $accountNumber = $accurateData['charField4'] ?? null; // Nomor rekening

            if ($bankName && $accountNumber) {
                $bank = BankModel::query()
                    ->where('akronim', $bankName)
                    ->select('id')
                    ->first();

                if ($bank) {
                    UserRekeningModel::query()->updateOrInsert(
                        [
                            'user_id' => $id,
                            'no_rekening' => $accountNumber, // Kondisi untuk pencarian
                        ],
                        [
                            'bank_id' => $bank->id,
                            'an' =>  $an,
                            'bank_kota' =>  $bank_kota,
                            'updated_at' => now(),
                            'created_at' => now(), // Hanya digunakan saat insert
                        ]
                    );

                    Log::info("Data bank untuk user dengan ID {$id} berhasil disimpan atau diperbarui.");
                } else {
                    Log::error("Bank dengan akronim '{$bankName}' tidak ditemukan.");
                }
            } else {
                Log::error("Data bank untuk user dengan ID {$id} tidak tersedia di Accurate.");
            }
        } catch (Exception $e) {
            Log::error("Terjadi kesalahan: " . $e->getMessage());
        }
    }
}
