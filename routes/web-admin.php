<?php

use App\Http\Controllers\Admin\EventController;
use App\Library\APIAccurate;
use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\MemberPointController;
use App\Http\Controllers\Admin\MemberSpentController;
use \App\Jobs\ManagePointsJob;
use App\Jobs\SyncPenjualanJob;


Route::get('testemplate', function () {
    return view('frontend.emails.tess');
});

use App\Jobs\SyncSendEmailUlangTahunJob;

// Route::get('/send-birthday-email', function () {
//     SyncSendEmailUlangTahunJob::dispatch();
//     return "Email ulang tahun telah dikirim!";
// });
Route::get('konfirmasi-hadir-event/{token}', [EventController::class, 'konfirmasi'])->name('konfirmasi-hadir-event');


Route::namespace("App\Http\Controllers\Admin")->group(function () {

    Route::prefix("admin")->group(function () {
        include __DIR__ . '/jobs/route_jobs.php';
        Route::get('penjualan/cariso', function () {
            $r = new APIAccurate();
            $noso = request('so');
            $response = $r->get('/api/sales-order/list.do?&sp.page=1&sp.sort=id|asc&filter.number.val[0]=' . $noso);

            return $response->json();
        });

        Route::get('testsimpan', function () {
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
                    'shipCountry' => $u->country
                ]);
                if ($r->status() == 200) {
                    $result = json_decode($r->body(), true);
                    $r = $result['r'];
                    UserModel::where('id', $u->id)->update(['id_accurate', $r['id']]);
                }
            }
        });

        Route::get('delivery-order/api', function () {
            $r = new APIAccurate();
            //            return $r->get('/api/sales-order/list.do?sp.page=1&filter.transDate.op=GREATER_EQUAL_THAN&filter.transDate.val[0]=19/01/2024&sp.sort=id|desc')->json();
            $id = request('id');

            if ($id == null) {
                $response = $r->get('/api/delivery-order/list.do?fields=' . urlencode('id') . '&sp.page=1&sp.sort=id|desc');
            } else {
                $response = $r->get('/api/delivery-order/detail.do?id=' . $id);
            }
            return $response->json();
        });

        Route::get('item/api', function () {
            $r = new APIAccurate();
            //            return $r->get('/api/sales-order/list.do?sp.page=1&filter.transDate.op=GREATER_EQUAL_THAN&filter.transDate.val[0]=19/01/2024&sp.sort=id|desc')->json();
            $id = request('id');

            if ($id == null) {
                $response = $r->get('/api/item/list.do?fields=' . urlencode('id') . '&sp.page=1&sp.sort=id|desc');
            } else {
                $response = $r->get('/api/item/detail.do?id=' . $id);
            }
            return $response->json();
        });

        Route::get('item-category/api', function () {
            $r = new APIAccurate();
            //            return $r->get('/api/sales-order/list.do?sp.page=1&filter.transDate.op=GREATER_EQUAL_THAN&filter.transDate.val[0]=19/01/2024&sp.sort=id|desc')->json();
            $id = request('id');

            if ($id == null) {
                $response = $r->get('/api/item-category/list.do?fields=' . urlencode('id') . '&sp.page=1&sp.sort=id|desc');
            } else {
                $response = $r->get('/api/item-category/detail.do?id=' . $id);
            }
            return $response->json();
        });

        Route::get('payment/api', function () {
            $r = new APIAccurate();
            //            return $r->get('/api/sales-order/list.do?sp.page=1&filter.transDate.op=GREATER_EQUAL_THAN&filter.transDate.val[0]=19/01/2024&sp.sort=id|desc')->json();
            $id = request('id');
            $keyword = request('keyword');
            if ($keyword != null) {
                $keyword = urlencode($keyword);
                $response = $r->get('/api/purchase-payment/list.do?fields=' . urlencode('id,number,charField1,approvalStatus') . '&sp.page=1&sp.sort=id|desc&filter.keywords.op=CONTAIN&filter.keywords.val[0]=' . $keyword);
                return $response->json()['d'];
            } else if ($id == null) {
                $response = $r->get('/api/purchase-payment/list.do?fields=' . urlencode('id,number,charField1') . '&sp.page=1&sp.sort=id|desc');
            } else {
                $response = $r->get('/api/purchase-payment/detail.do?id=' . $id);
                return $response->json()['d'];
            }

            return $response->json()['d'][0];
        });

        Route::get('sales-return/api', function () {
            $r = new APIAccurate();
            //            return $r->get('/api/sales-order/list.do?sp.page=1&filter.transDate.op=GREATER_EQUAL_THAN&filter.transDate.val[0]=19/01/2024&sp.sort=id|desc')->json();
            $id = request('id');

            $keyword = request('keyword');
            if ($keyword != null) {
                $keyword = urlencode($keyword);
                $response = $r->get('/api/sales-return/list.do?fields=' . urlencode('id,number,charField1,approvalStatus') . '&sp.page=1&sp.sort=id|desc&filter.keywords.op=CONTAIN&filter.keywords.val[0]=' . $keyword);
            } else if ($id === null) {
                $response = $r->get('/api/sales-return/list.do?fields=' . urlencode('id') . '&sp.page=1&sp.sort=id|desc');
            } else {
                $response = $r->get('/api/sales-return/list.do?fields=' . urlencode('id,number,charField1,approvalStatus,customerId') . '&sp.page=1&filter.customerId.op=GREATER_EQUAL_THAN&filter.customerId.val[0]=' . urlencode('53353') . '&filter.transDate.op=GREATER_EQUAL_THAN&filter.transDate.val[0]=' . urlencode('05/10/2024') . '&sp.sort=id|desc');
                // $response = $r->get('/api/sales-return/detail.do?id=' . $id);
            }

            return $response->json();
        });


        Route::get('member/api', function () {
            $r = new APIAccurate();
            //            return $r->get('/api/sales-order/list.do?sp.page=1&filter.transDate.op=GREATER_EQUAL_THAN&filter.transDate.val[0]=19/01/2024&sp.sort=id|desc')->json();
            $id = request('id');

            $custno = request('customerNo');

            if ($custno != null) {
                $response = $r->get('/api/customer/list.do?fields=id,name,customerNo,email,npwpNo,category,lastUpdate&filter.keywords.op=EQUAL&filter.keywords.val[0]=' . urlencode($custno) . '&sp.page=1&sp.sort=id|desc');
            } else if ($id == null) {

                $response = $r->get('/api/customer/list.do?fields=id,name,customerNo,category,email,npwpNo,lastUpdate&sp.page=1&sp.sort=id|desc');
            } else {
                // $response = $r->get('/api/customer/detail.do?id=' . $id);
                $response = $r->get('/api/customer/detail.do?customerNo=' . $id);
            }
            return $response->json();
        });

        Route::get('customer-category/api', function () {
            $r = new APIAccurate();
            //            return $r->get('/api/sales-order/list.do?sp.page=1&filter.transDate.op=GREATER_EQUAL_THAN&filter.transDate.val[0]=19/01/2024&sp.sort=id|desc')->json();
            $id = request('id');
            if ($id == null) {
                $response = $r->get('/api/customer-category/list.do?fields=' . urlencode('id') . '&sp.page=1&sp.sort=id|desc');
            } else {
                $response = $r->get('/api/customer-category/detail.do?id=' . $id);
            }
            return $response->json();
        });

        Route::get('sales-invoice/api', function () {
            $r = new APIAccurate();

            // Retrieve the "number" parameter from the request
            $number = request('number');
            $id = request('id');
            // dd($number);
            if ($id != null) {
                // If no "number" parameter is provided, return a default list of sales invoices with DP
                // $response = $r->get('/api/sales-invoice/list.do?fields=' . urlencode('id,number,invoiceDp') . '&filter.invoiceDp=true');
                $response = $r->get('/api/sales-invoice/detail.do?id=' . urlencode($id));
            } else {
                // If "number" is provided, filter the sales invoices by the provided "number"
                $response = $r->get('/api/sales-invoice/list.do?fields=' . urlencode('id,number,invoiceDp') . '&filter.invoiceDp=true&filter.number.op=EQUAL&filter.number.val[0]=' . urlencode($number) . '&sp.page=1&sp.sort=id|asc');
                // $response = $r->get('/api/sales-invoice/list.do?fields=' . urlencode('id,number,invoiceDp') . '&filter.invoiceDp=true&filter.transDate.val[0]=24/12/2024');
            }

            // Return the JSON response
            return $response->json();
        });

        Route::get('shipment/api', function () {
            $r = new APIAccurate();
            //            return $r->get('/api/sales-order/list.do?sp.page=1&filter.transDate.op=GREATER_EQUAL_THAN&filter.transDate.val[0]=19/01/2024&sp.sort=id|desc')->json();
            $id = request('id');
            if ($id == null) {
                $response = $r->get('/api/shipment/list.do?fields=' . urlencode('id') . '&sp.page=1&sp.sort=id|desc');
            } else {
                $response = $r->get('/api/shipment/detail.do?id=' . $id);
            }
            return $response->json();
        });

        Route::get('penjualan/api', function () {
            $r = new APIAccurate();
            //            return $r->get('/api/sales-order/list.do?sp.page=1&filter.transDate.op=GREATER_EQUAL_THAN&filter.transDate.val[0]=19/01/2024&sp.sort=id|desc')->json();
            $id = request('id');
            $so = request("so");
            $tgl = request("tgl");
            $tgl2 = request("tgl2");
            $page = (int)request('page', 1);
            if ($so != null) {
                $url = '/api/sales-order/list.do?sp.page=' . $page . '&filter.number.op=EQUAL&filter.number.val[0]=' . urlencode($so) . '&sp.sort=id|desc';
                //
                $response = $r->get($url);
            } else if ($tgl != null && $tgl2 != null) {
                $url = '/api/sales-order/list.do?fields=' . urlencode('id,customerNo,transDate') . '&sp.page=' . $page . '&filter.transDate.op=BETWEEN&filter.transDate.val[0]=' . urlencode($tgl) . '&filter.transDate.val[1]=' . urlencode($tgl2) . '&sp.sort=transDate|asc';
                //
                $response = $r->get($url);
            } else if ($tgl != null) {
                $url = '/api/sales-order/list.do?fields=' . urlencode('id,customerNo,transDate') . '&sp.page=' . $page . '&filter.transDate.op=GREATER_EQUAL_THAN&filter.transDate.val[0]=' . urlencode($tgl) . '&sp.sort=transDate|asc';
                //
                $response = $r->get($url);
            } else if ($id == null) {
                $response = $r->get('/api/sales-order/list.do?fields=' . urlencode('id,customerNo,transDate,totalAmount,statusName,shipDate,number,charField10,description,approvalStatus') . '&sp.page=1&sp.sort=id|desc');
            } else {
                $response = $r->get('/api/sales-order/detail.do?id=' . $id);
            }
            return $response->json();
        });

        Route::get('produk/api', function () {
            $r = new APIAccurate();
            $id = request('id');
            if ($id == null) {
                $response = $r->get('/api/item/list.do?fields=' . urlencode('id,customerNo,transDate,totalAmount,statusName,shipDate,number,charField10,description,approvalStatus') . '&sp.page=1&sp.sort=id|asc');
            } else {
                $response = $r->get('/api/item/detail.do?id=' . $id);
            }
            return $response->json();
        });

        Route::get('memb', function () {
            $r = new APIAccurate();
            $data = UserModel::orderBy('id_accurate', 'desc')->first();

            if ($data != null) {
                $lastupdate = Carbon::parse($data->last_update_accurate)->format('d/m/Y H:i:s');
                $id = 27900; //$data->id;
                $query = '/api/customer/list.do?fields=id,name,customerNo,email,npwpNo,lastUpdate&filter.id.op=GREATER_THAN&filter.id.val[0]=' . $id . '&sp.page=1&sp.sort=id|asc';

                $response = $r->get($query);
                return $response->json();
            }
            $id = request('id');
            if ($id == null) {
                $response = $r->get('/api/customer/list.do?fields=id,name,customerNo,email,npwpNo,lastUpdate&id=' . $id . '&sp.page=1&sp.sort=id|asc');
            } else {
                $response = $r->get('/api/customer/detail.do?id=' . $id);
            }
            return $response->json();
        });

        Route::prefix("auth")->group(function () {
            Route::get("/", "AuthController@index");
            Route::post("/", "AuthController@auth");
            Route::delete("/", "AuthController@logout")->middleware("auth-admin");
            Route::patch("/", "AuthController@forgot");
        });

        Route::prefix('notification')->group(function () {
            Route::get('/', 'NotificationController@index');
        });

        Route::middleware("auth-admin")->group(function () {
            Route::get('foto/{id}.png', 'UserController@photo');

            Route::prefix("dashboard")->group(function () {
                Route::get("/", "DashboardController@index");
            });

            Route::prefix("profile")->group(function () {
                Route::get("/", "ProfileController@index");
                Route::patch("/", "ProfileController@update");
            });

            Route::prefix("bank")->group(function () {
                Route::get("/", "BankController@index");
                Route::get("/data-source", "BankController@datasource");
                Route::get("/{id}.png", "BankController@logo");
                Route::delete("/", "BankController@delete");
            });

            Route::prefix("cabang")->group(function () {
                Route::get("/", "CabangController@index");
                Route::get("/data-source", "CabangController@datasource");
                Route::get("/{id}", "CabangController@edit");
                Route::post("/", "CabangController@create");
                Route::delete("/", "CabangController@delete");
            });

            Route::prefix("level")->group(function () {
                Route::get("/", "LevelMemberController@index");
                Route::get("/data-source", "LevelMemberController@datasource");
                Route::get("/{id}", "LevelMemberController@edit");
                Route::post("/", "LevelMemberController@create");
                Route::delete("/", "LevelMemberController@delete");
            });

            // Route::prefix("redeem")->group(function () {
            //     Route::get("/", "RedeemController@index");
            //     Route::get("/data-source", "RedeemController@datasource");
            //     Route::get("/data-source-{state}", "RedeemController@datasource");
            //     Route::get("/{id}", "RedeemController@edit");
            //     Route::post("/", "RedeemController@create");
            //     Route::delete("/", "RedeemController@delete");
            // });
            Route::prefix("redeem")->group(function () {
                // Halaman utama
                Route::get("/", "RedeemController@index")->name('redeempoint.index');

                // Hitung jumlah record di setiap tab
                Route::get("/count-tab/{step}", "RedeemController@countTab")->name('redeempoint.countTab');

                // Data source untuk DataTable
                Route::get("/data-source", "RedeemController@datasource")->name('redeempoint.datasource');
                Route::get("/data-source-proses", "RedeemController@datasourceProses")->name('redeempoint.datasource.proses');
                Route::get("/data-source-acc", "RedeemController@datasourceAcc")->name('redeempoint.datasource.acc');
                Route::get("/data-source-tolak", "RedeemController@datasourceTolak")->name('redeempoint.datasource.tolak');

                // Perubahan status
                Route::post("/status/pengajuan", "RedeemController@statusPengajuan")->name('redeempoint.status.pengajuan');
                Route::post("/status/acc", "RedeemController@statusAcc")->name('redeempoint.status.acc');
                Route::post("/status/tolak", "RedeemController@statusTolak")->name('redeempoint.status.tolak');
                Route::post("/status/draft", "RedeemController@statusDraft")->name('redeempoint.status.tolak');

                // (Opsional) Hapus / Tolak
                // Kalau memang ingin meniadakan method delete dan diganti tolak, hapus route di bawah ini.
                Route::delete("/", "RedeemController@delete")->name('redeempoint.delete');
            });


            Route::prefix("member")->group(function () {
                Route::get("/", "MemberController@index");
                Route::get("/select2prof", "MemberController@select2profesional");
                Route::get("/select2", "MemberController@select2");
                Route::get("/data-source", "MemberController@datasource");
                Route::get("/get-points/{userId}", "MemberController@getPoints");
                Route::get("/info/{id}", "MemberController@info");
                Route::get("foto/{id}", "MemberController@foto");
                Route::get("/{id}", "MemberController@edit");
                Route::post("/", "MemberController@create");
                Route::delete("/", "MemberController@delete");
                Route::get("/sync-member/{id}", "MemberController@syncMemberById")->name('member.sync');
            });

            Route::prefix("approval")->group(function () {
                Route::get("/", "MemberController@listapproval");
                Route::get("/data-source-submit", "MemberController@datasource_submit");
                Route::get("/data-source-approved", "MemberController@datasource_approval");
                Route::get("/data-source-reject", "MemberController@datasource_reject");
                Route::get("/{id}", "MemberController@show");
                Route::delete("/", "MemberController@delete_approval");
            });

            Route::prefix("promo")->group(function () {
                Route::get("/", "PromoController@index");
                Route::get("/select2", "PromoController@select2");
                Route::get("/data-source", "PromoController@datasource");
                Route::get("/data-source-detail", "PromoController@datasource_detail");
                Route::get("/info-produk/{id}/{lmi}", "PromoController@infoProduk");
                Route::get("/{id}", "PromoController@detail");
                Route::post("/", "PromoController@create");
                Route::delete("/", "PromoController@delete");
            });
            Route::prefix('member-points')->group(function () {
                Route::get('/', [MemberPointController::class, 'index'])->name('admin.member-points.index');
                Route::get('/data', [MemberPointController::class, 'getData'])->name('admin.member-points.datasource');
                Route::get('/details/{id}', [MemberPointController::class, 'memberDetail'])->name('admin.member-points.details');

                // Tambahkan route untuk reset dan update
                Route::post('/reset', function () {
                    $job = new ManagePointsJob('reset');
                    $job->handle(); // Jalankan langsung tanpa antrean
                    return response()->json(['status' => 'success', 'message' => 'Proses reset poin berhasil dijalankan.']);
                })->name('admin.member-points.reset');
                Route::post('/update', function () {
                    $job = new ManagePointsJob('update');
                    $job->handle();
                    return response()->json(['status' => 'success', 'message' => 'Proses pembaruan poin berhasil dijalankan.']);
                })->name('admin.member-points.update');
            });

            Route::prefix('member-spent')->group(function () {
                Route::get('/', [MemberSpentController::class, 'index'])->name('admin.member-spent.index');
                Route::get('/data', [MemberSpentController::class, 'getData'])->name('admin.member-spent.datasource');
            });


            Route::prefix("reward")->group(function () {
                Route::get("/", "RewardController@index");
                Route::get("/select2", "RewardController@select2");
                Route::get("/data-source", "RewardController@datasource");
                Route::get("/get-points/{rewardId}", "RewardController@getPoints");
                Route::get("/pic/{id}", "RewardController@getPic");
                Route::get("/{id}", "RewardController@edit");
                Route::post("/", "RewardController@create");
                Route::delete("/", "RewardController@delete");
            });

            Route::prefix("kategori")->group(function () {
                Route::get("/", "KategoriArtikelController@index");
                Route::get("/select2", "KategoriArtikelController@select2");
                Route::get("/data-source", "KategoriArtikelController@datasource");
                Route::get("/{id}", "KategoriArtikelController@edit");
                Route::post("/", "KategoriArtikelController@create");
                Route::delete("/", "KategoriArtikelController@delete");
            });

            Route::prefix("artikel")->group(function () {
                Route::get("/", "ArtikelController@index");
                Route::get("/data-source", "ArtikelController@datasource");
                Route::get("/image/{id}.png", "ArtikelController@image");
                Route::get("/{id}", "ArtikelController@edit");
                Route::post("/", "ArtikelController@create");
                Route::delete("/", "ArtikelController@delete");
            });

            Route::prefix("event")->group(function () {
                Route::get("/", "EventController@index");
                Route::get("/data-source", "EventController@datasource");
                Route::get("/images/{id}.png", "EventController@images");
                Route::get("/{id}", "EventController@edit");
                Route::post("/", "EventController@create");
                Route::delete("/", "EventController@delete");
                Route::get("/konfirmasi/{token}", "EventController@konfirmasi_hadir");
                Route::get("/data-hadir/{id}", "EventController@data_hadir");
            });

            Route::prefix("gift-type")->group(function () {
                Route::get("/", "GiftTypeController@index");
                Route::get("/data-source", "GiftTypeController@datasource");
                Route::get("/{id}", "GiftTypeController@edit");
                Route::post("/", "GiftTypeController@create");
                Route::delete("/", "GiftTypeController@delete");
            });

            Route::prefix("gift")->group(function () {
                Route::get("/", "GiftController@index");
                Route::get("/data-source", "GiftController@datasource");
                Route::get("/{id}", "GiftController@edit");
                Route::post("/", "GiftController@create");
                Route::delete("/", "GiftController@delete");
            });

            Route::prefix("kategori-produk")->group(function () {
                Route::get("/", "KategoriProdukController@index");
                Route::get("/data-source", "KategoriProdukController@datasource");
                Route::get("/select2", "KategoriProdukController@select2");
                Route::get("/{id}", "KategoriProdukController@edit");
                Route::post("/", "KategoriProdukController@create");
                Route::delete("/", "KategoriProdukController@delete");
            });

            Route::prefix("produk")->group(function () {
                Route::get("/", "ProdukController@index");
                Route::get("/select2", "ProdukController@select2");
                Route::get("/select2merk", "ProdukController@select2merk");
                Route::get("/data-source", "ProdukController@datasource");
                Route::get("/image/{id}", "ProdukController@image");
                Route::get("/{id}", "ProdukController@edit");
                Route::post("/", "ProdukController@create");
                Route::delete("/", "ProdukController@delete");
            });

            Route::prefix("produk-image")->group(function () {
                Route::get("/data-source", "ProdukImageController@datasource");
                Route::get("/image/{id}.png", "ProdukImageController@image");
                Route::get("/{id}", "ProdukImageController@index");
                Route::delete("/", "ProdukController@delete");
            });

            Route::prefix("slider")->group(function () {
                Route::get("/data-source", "SliderController@datasource");
                Route::get("/image/{id}.png", "SliderController@image");
                Route::get("/", "SliderController@index");
                Route::delete("/", "SliderController@delete");
            });

            Route::prefix("video")->group(function () {
                Route::get("/data-source", "VideoController@datasource");
                Route::get("/image/{id}.png", "VideoController@image");
                Route::get("/", "VideoController@index");
                Route::delete("/", "VideoController@delete");
            });

            Route::prefix("katalog-produk")->group(function () {
                Route::get("/data-source", "KatalogProdukController@datasource");
                Route::get("/image/{id}.png", "KatalogProdukController@image");
                Route::get("/berkas/{id}.pdf", "KatalogProdukController@berkas");
                Route::get("/", "KatalogProdukController@index");
                Route::delete("/", "KatalogProdukController@delete");
            });

            Route::prefix('payment-made')->group(function () {
                Route::get('/', 'PaymentMadeController@index');
                Route::get('/data-source', 'PaymentMadeController@datasource');
                Route::delete("/", "PaymentMadeController@delete");
            });


            Route::prefix('retur-penjualan')->group(function () {
                Route::get('/', 'ReturPenjualanController@index');
                Route::get('/data-source', 'ReturPenjualanController@datasource');
                Route::delete("/", "ReturPenjualanController@delete");
            });


            Route::prefix("penjualan")->group(function () {
                Route::get("/", "PenjualanProdukController@index");
                Route::get("/data-source-detail", "PenjualanProdukController@datasource_detail");
                Route::get("/data-source", "PenjualanProdukController@datasource");
                Route::get("/select2nomor_so", "PenjualanProdukController@select2nomor_so");
                Route::get("/form", "PenjualanProdukController@form");
                Route::get("/detail/{id}", "PenjualanProdukController@detail");
                Route::get("/json-detail/{id}", "PenjualanProdukController@json_detail");
                Route::get("/{id}", "PenjualanProdukController@edit");
                Route::post("/resync", "PenjualanProdukController@resync");
                Route::post("/", "PenjualanProdukController@create");
                Route::delete("/detail", "PenjualanProdukController@delete_detail");
                Route::delete("/", "PenjualanProdukController@delete");
            });
            Route::get('/sync-all/{tgl1}/{tgl2}', function ($tgl1, $tgl2) {
                try {
                    // Validasi format tanggal
                    $carbonTgl1 = Carbon::createFromFormat('d-m-Y', $tgl1);
                    $carbonTgl2 = Carbon::createFromFormat('d-m-Y', $tgl2);

                    // Dispatch job
                    SyncPenjualanJob::dispatch($carbonTgl1->format('d/m/Y'), true, '', $carbonTgl2->format('d/m/Y'));

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Sinkronisasi semua data berhasil dimulai.'
                    ]);
                } catch (\Exception $e) {
                    Log::error("Error dalam sinkronisasi semua data: {$e->getMessage()}", [
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                    ]);

                    return response()->json([
                        'status' => 'error',
                        'message' => 'Terjadi kesalahan saat memulai sinkronisasi.',
                    ], 500);
                }
            })->name('sync-all');


            Route::prefix("fee")->group(function () {
                Route::get("/", "FeeController@index");
                Route::get("/count-tab/{step}", 'FeeController@countTab');
                Route::get("/sum-tab/{step}", 'FeeController@sumTab');
                Route::get("/data-source", "FeeController@datasource");
                Route::get("/data-source-pengajuan", "FeeController@datasource_pengajuan");
                Route::get("/data-source-proses", "FeeController@datasource_proses");
                Route::get("/data-source-setujui", "FeeController@datasource_setujui");
                Route::get("/data-source-selesai", "FeeController@datasource_selesai");
                Route::get("/data-source-dp", "FeeController@datasource_dp");
                Route::get("/data-source-outstanding", "FeeController@datasource_outstanding");
                Route::get("/report-outstanding", "FeeController@report_outstanding");
                Route::get("/image/{id}.png", "FeeController@image");
                Route::get("/download/fee.xls", "FeeController@unduhXLS");
                Route::get("/download/fee-{status}.xls", "FeeController@unduhXLS");
                Route::get("/download-csv/fee-{status}.xls", "FeeController@unduhCSV");
                Route::get("/{id}", "FeeController@form");
                Route::post("/status/{status}", "FeeController@status_set");
                Route::delete("/remove/{status}", "FeeController@remove_status");
                Route::delete("/", "FeeController@delete");
                Route::patch('/buatresume', 'FeeController@buatResume');
                Route::get("/proses-dp/{id}", "FeeController@prosesDP");
            });


            Route::prefix("hak-akses")->group(function () {
                Route::get("/", "HakAksesController@index");
                Route::get("/select2", "HakAksesController@select2role");
                Route::get("/data-source", "HakAksesController@datasource");
                Route::get("/{id}", "HakAksesController@edit");
                Route::post("/", "HakAksesController@create");
                Route::delete("/", "HakAksesController@delete");
            });

            Route::prefix("role-access-right")->group(function () {
                Route::get("/data-source/{roleid}", "HakAksesController@datasource_roleacessright");
                Route::get("/select2", "HakAksesController@select2_roleaccessright");
                Route::get("/{roleid}", "HakAksesController@roleAccessRight");
                Route::delete("/{roleid}", "HakAksesController@delete_role_access_right");
            });


            Route::prefix("pengguna")->group(function () {
                Route::get("/", "UserController@index");
                Route::get("/data-source", "UserController@datasource");
                Route::get("/photo/{id}.png", "UserController@photo");
                Route::get("/{id}", "UserController@edit");
                Route::post("/", "UserController@create");
                Route::delete("/", "UserController@delete");
            });

            Route::prefix("log")->group(function () {
                Route::get("/", "LogController@index");
                Route::get("/data-source", "LogController@datasource");
                Route::get("/{id}", "UserController@show");
            });

            Route::prefix("settings")->group(function () {
                Route::get("/", "SettingsController@index");
            });


            Route::prefix("report")->group(function () {
                Route::get("/fee-member", "ReportController@feeMember");
                Route::post("/fee-member/show", "ReportController@feeMemberShow");
                Route::post("/fee-member/xls", "ReportController@feeMemberXLS");

                Route::get("/fee-kategori", "ReportController@feeKategori");
                Route::post("/fee-kategori/show", "ReportController@feeKategoriShow");
                Route::post("/fee-kategori/xls", "ReportController@feeKategoriXLS");

                Route::get("/fee-merk", "ReportController@feeMerkBarang");
                Route::post("/fee-merk/show", "ReportController@feeMerkBarangShow");
                Route::post("/fee-merk/xls", "ReportController@feeMerkBarangXLS");
            });
        });
    });
});
