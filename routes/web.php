<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/cek', function () {
    return view('frontend.emails.aktivasi');
});
// wow

Route::namespace('App\Http\Controllers\Frontend')->group(function () {
    Route::get('/', 'Login@index');
    Route::get('/aktivasi-akun/{token}', 'Login@aktivasiakun');
    Route::get('/reset-password-akun/{token}', 'Login@resetpasswordakun');
    Route::get('/ubahpassword', 'Login@ubahpassword');

    Route::prefix('login')->group(function () {
        Route::get('/', 'Login@index');
        Route::post('validasi', 'Login@validasi');
        Route::post('forgetacc', 'Login@forgetacc');
        Route::post('resetpasswordacc', 'Login@resetpasswordacc');
        Route::post('registeracc', 'Login@registeracc');
        Route::post('resetpasslama', 'Login@resetpasslama');
        Route::get('forget', 'Login@forget');
        Route::get('register', 'Login@register');

        Route::post('logout', 'Login@logout');
    });

    Route::prefix("produk-img")->group(function () {
        Route::get("/data-source", "ProdukImage@datasource");
        Route::get("/image/{id}.png", "ProdukImage@image");
        Route::get("/imageprimary/{id}.png", "ProdukImage@imagePrimary");
        Route::get("/{id}", "ProdukImage@index");
    });
    Route::prefix("article")->group(function () {
        Route::get("/image/{id}.png", "Article@image");
    });

    Route::prefix("fslider")->group(function () {
        Route::get("/image/{id}.png", "Home@image");
    });

    Route::prefix("katalog-produk")->group(function () {
        Route::get("/image/{id}.png", "Katalog@image");
        Route::get("/berkas/{id}.pdf", "Katalog@berkas");
    });
});


Route::namespace('App\Http\Controllers\Frontend')->middleware('auth-member')->group(function () {
    Route::get('/home', 'Home@index');
    Route::get('/reward', 'Reward@index');
    Route::get("/reward-image/{id}.png", "Reward@image");
    Route::get('/qrcode/{url}', 'Reward@showQrCode');
    Route::get('/product', 'Product@index');
    Route::post('/productlikes', 'Product@like_product');
    Route::get('/product-detail/{id}', 'Product@productdetail');

    Route::get('/informasi', 'News@index');
    Route::get('/event', 'News@event');
    Route::get("detail-article/{id}", "News@detail");
    Route::get("event-detail/{id}", "News@detevent");
    Route::post("join-event/{event_id}", "News@joinevent");
    Route::post("/post-comment", "News@postcomment");
    Route::get("image-event/{id}", "News@imageevent");
    Route::get("image-eventdetail/{id}", "News@imageeventdetail");

    Route::get("profile-data", "Profile@index");
    Route::get("profile", "Profile@data_pribadi");
    Route::post("profile-update", "Profile@updateprofile");
    Route::post("upload-fotoprofile", "Profile@upload");
    Route::get("profile-image/{id}.png", "Profile@profileimage");

    Route::post("klaim-reward/{rewardId}", "Reward@klaimReward");

    Route::post("komentar-produk", "Product@komentarProduk");

    Route::get("notificationssss", "Notification@index");
    Route::get("notificationsgg", "Notification@read");
    Route::get("notifications", "Notification@baca");
    Route::get("ceknotif/{id}", "Notification@ceknotif");
    Route::get("datanotif", "Notification@datanotif");
});
