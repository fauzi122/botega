<?php
use Illuminate\Support\Facades\Route;
use App\Jobs\CalcMemberExpenseJob;
use App\Jobs\ResetPointPenjualangJob;
use App\Jobs\ResumeDashboarJob;
use App\Jobs\SendEmailAktivasi;
use App\Jobs\SendEmailKonfirmasi;
use App\Jobs\SendEmailResetPassword;
use App\Jobs\SendNotifEmailFeeHasPaidJob;
use App\Jobs\SendNotifFeeJob;
use App\Jobs\SendNotifUlangTahun;
use App\Jobs\SyncMemberJob;
use App\Jobs\SyncNoFakturFeeMemberJob;
use App\Jobs\SyncPenjualanJob;
use App\Jobs\SyncSaleReturnJob;
use App\Jobs\SyncSendEmailUlangTahunJob;
use App\Jobs\UpdateProfileJob;

Route::get('/calc-member-expense', function () {
    CalcMemberExpenseJob::dispatch();
    return "CalcMemberExpenseJob telah dijalankan!";
});

Route::get('/reset-point-penjualang', function () {
    ResetPointPenjualangJob::dispatch();
    return "ResetPointPenjualangJob telah dijalankan!";
});

Route::get('/resume-das', function () {
    ResumeDashboarJob::dispatch();
    return "ResumeDashboarJob telah dijalankan!";
});

Route::get('/send-email-aktivasi', function () {
    SendEmailAktivasi::dispatch();
    return "SendEmailAktivasi telah dikirim!";
});

Route::get('/send-email-konfirmasi', function () {
    SendEmailKonfirmasi::dispatch();
    return "SendEmailKonfirmasi telah dikirim!";
});

Route::get('/send-email-reset-password', function () {
    SendEmailResetPassword::dispatch();
    return "SendEmailResetPassword telah dikirim!";
});

Route::get('/send-notif-email-fee-has-paid', function () {
    SendNotifEmailFeeHasPaidJob::dispatch();
    return "SendNotifEmailFeeHasPaidJob telah dikirim!";
});

Route::get('/send-notif-fee', function () {
    SendNotifFeeJob::dispatch();
    return "SendNotifFeeJob telah dikirim!";
});

Route::get('/send-notif-ulang-tahun', function () {
    SendNotifUlangTahun::dispatch();
    return "SendNotifUlangTahun telah dikirim!";
});

Route::get('/sync-member', function () {
    SyncMemberJob::dispatch();
    return "SyncMemberJob telah dijalankan!";
});

Route::get('/sync-no-faktur-fee-member', function () {
    SyncNoFakturFeeMemberJob::dispatch();
    return "SyncNoFakturFeeMemberJob telah dijalankan!";
});

Route::get('/sync-penjualan', function () {
    SyncPenjualanJob::dispatch();
    return "SyncPenjualanJob telah dijalankan!";
});

Route::get('/sync-sale-return', function () {
    SyncSaleReturnJob::dispatch();
    return "SyncSaleReturnJob telah dijalankan!";
});

Route::get('/sync-send-email-ulang-tahun', function () {
    SyncSendEmailUlangTahunJob::dispatch();
    return "SyncSendEmailUlangTahunJob telah dikirim!";
});

Route::get('/update-profile', function () {
    UpdateProfileJob::dispatch();
    return "UpdateProfileJob telah dijalankan!";
});
