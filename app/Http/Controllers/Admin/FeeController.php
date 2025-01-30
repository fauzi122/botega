<?php

namespace App\Http\Controllers\Admin;

use App\Exports\FeeProfesionalRekapAll;
use App\Exports\FeeProfessionalPerUser;
use App\Exports\PDFRekapFee;
use App\Exports\PengajuanFeeCSV;
use App\Exports\ResumeFeeExport;
use App\Http\Controllers\Controller;
use App\Jobs\SendNotifFeeJob;
use App\Jobs\SendDownPaymentJob;
use App\Library\ValidatedPermission;
use App\Models\ClaimItemTransactionModel;
use App\Models\DetailDeliveryOrderModel;
use App\Models\DetailTransactionModel;
use App\Models\FeeNumberModel;
use App\Models\FeeNumberDP;
use App\Models\FeePaymentMadeModel;
use App\Models\FeeProfessionalModel;
use App\Models\FeeSplitModel;
use App\Models\UserModel;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Excel;
use Illuminate\Support\Facades\Log;

class FeeController extends Controller
{
    public function index()
    {
        return view('admin.fee.table');
    }

    public function datasource()
    {
        if (!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_FEE)) {
            return [];
        }

        return datatables(
            FeeProfessionalModel::resume()
                ->whereNull(['dt_pengajuan', 'dt_acc', 'dt_finish'])
        )->toJson(true);
    }

    public function datasource_pengajuan()
    {

        if (!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_FEE)) {
            return [];
        }

        return datatables(
            FeeProfessionalModel::resume()
                ->whereNotNull('dt_pengajuan')
                ->whereNull(['dt_acc', 'dt_finish'])
        )->toJson(true);
    }

    //    public function datasource_proses(){
    //
    //        if(!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_FEE)){
    //            return [];
    //        }
    //
    //        return datatables(FeeProfessionalModel::resumeRekening()
    //                    ->whereNotNull('dt_proses')
    //                    ->whereNull('dt_acc')
    //                )->toJson(true);
    //    }

    public function datasource_setujui()
    {

        if (!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_FEE)) {
            return [];
        }


        $r = datatables(
            FeeProfessionalModel::resumeRekening()
                ->whereNotNull('dt_acc')
                ->whereNull(['dt_finish', 'dt_dp'])
        )->toArray();
        //        $id = array_map(function($item){
        //            return $item['fee_number_id'];
        //        }, $r['data']);
        //
        //        $feenumber = FeeNumberModel::query()->whereIn("id", $id)->get()->mapToGroups(function($item){
        //            return [$item->id => $item];
        //        });
        //        $r['data'] = array_map(function($item) use($feenumber){
        //            $pm = $feenumber[$item['fee_number_id']]->first();
        //            $item['payment_made'] = $pm->payment_made;
        //            return $item;
        //        }, $r['data']);
        // dd($r);
        return response()->json($r);
    }

    public function datasource_selesai()
    {

        if (!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_FEE)) {
            return [];
        }

        return datatables(
            FeeProfessionalModel::resumeRekening()
                ->whereNotNull('dt_finish')
                ->whereNull('dt_dp')
        )->toJson(true);
    }

    public function datasource_dp()
    {

        if (!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_FEE)) {
            return [];
        }

        return datatables(
            FeeProfessionalModel::resumeDP()
                ->whereNotNull(['dt_dp'])
        )->toJson(true);
    }

    public function datasource_outstanding()
    {
        if (!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_FEE)) {
            return [];
        }

        return datatables(
            FeeSplitModel::outstandingFee()
        )->editColumn('trx_at', function ($e) {
            return Carbon::parse($e['trx_at'])->translatedFormat('l, d F Y');
        })
            ->toJson(true);
    }

    public function delete()
    {

        if (!ValidatedPermission::authorize(ValidatedPermission::HAPUS_DATA_FEE)) {
            return;
        }

        $id = \request('id');
        $r = false;
        foreach ($id as $ii) {
            $ss = explode("|", $ii);
            $userid = $ss[0];
            $feenumberid = $ss[1];
            $r = FeeProfessionalModel::query()
                ->where('member_user_id', $userid)
                ->where('fee_number_id', $feenumberid)
                ->whereNull(['dt_pengajuan',  'dt_acc', 'dt_finish'])->get();
            foreach ($r as $rowfee) {
                if ($rowfee?->detail_delivery_id  != null) {
                    DetailDeliveryOrderModel::query()->where('id', $rowfee->detail_delivery_id)->update(['status_claim' => null]);
                    ClaimItemTransactionModel::query()->where([
                        'member_user_id' => $userid,
                        'detail_delivery_order_id' => $rowfee->detail_delivery_id,
                    ])->delete();
                    FeeSplitModel::query()->where([
                        'member_user_id' => $userid,
                        'detail_delivery_order_id' => $rowfee->detail_delivery_id,
                    ])->delete();
                } else {
                    ClaimItemTransactionModel::query()->where([
                        'member_user_id' => $userid,
                        'detail_transactions_id' => $rowfee->detail_transaction_id
                    ])->delete();
                    FeeSplitModel::query()->where([
                        'member_user_id' => $userid,
                        'detail_transaction_id' => $rowfee->detail_transaction_id
                    ])->delete();
                }
                DetailTransactionModel::query()->where('id', $rowfee->detail_transaction_id)->update(['status_claim' => null]);
            }

            $r = FeeProfessionalModel::query()
                ->where('member_user_id', $userid)
                ->where('fee_number_id', $feenumberid)
                ->whereNull(['dt_pengajuan',   'dt_acc', 'dt_finish'])->delete();

            FeeNumberModel::query()
                ->where('member_user_id', $userid)
                ->where('id', $feenumberid)
                ->whereNull(['dt_pengajuan',   'dt_acc', 'dt_finish'])->delete();
        }

        FeeSplitModel::normalizeFeeProfessional();

        LogController::writeLog(ValidatedPermission::HAPUS_DATA_FEE, 'Hapus data fee ', $id);

        return response()->json([
            'data' => $r
        ]);
    }

    public function gantiStatus($fieldName, $status = '')
    {
        try {
            if (!ValidatedPermission::authorize(ValidatedPermission::UBAH_DATA_FEE)) {
                return;
            }

            $id = \request('id');
            $r = false;
            // dd($id);
            foreach ($id as $ii) {
                $ss = explode("|", $ii);
                $userid = $ss[0];
                $feenumberid = $ss[1];
                $changeMember = isset($ss[2]) ? $ss[2] : null;
                $nomorSO = isset($ss[3]) ? $ss[3] : null; // Ambil nomor SO jika dikirim
                // dd($changeMember);
                $r = FeeProfessionalModel::query()
                    ->where('member_user_id', $userid)
                    ->where('fee_number_id', $feenumberid)
                    ->whereNull($fieldName)
                    ->update([
                        'admin_user_id' => session('admin')?->id,
                        'updated_at' => now(),
                        $fieldName => now(),
                        'harus_dibayar' => $fieldName === 'dt_finish' ? 0 : \DB::raw('total_pembayaran'),
                    ]);

                FeeNumberModel::query()
                    ->where('id', $feenumberid)
                    ->whereNull($fieldName)
                    ->update([
                        'admin_user_id' => session('admin')?->id,
                        $fieldName => now(),
                        'updated_at' => now(),
                    ]);
                // Kirim data ke API jika status adalah `dt_dp`
                if ($fieldName === 'dt_dp') {
                    // Ambil data dari tabel `fee_number`
                    $feeNumber = FeeNumberModel::find($feenumberid);

                    // Ambil data pengguna berdasarkan userid dan changeMember (jika ada)
                    $user = UserModel::find($userid);
                    $changeUser = isset($changeMember) ? UserModel::find($changeMember) : null;

                    if ($feeNumber && $user) {
                        // Tentukan customerNo dan charField4
                        // dd($changeMember ? 'ada' : 'ga');
                        $customerNo = $changeMember ? $changeUser->id_no : $user->id_no; // Gunakan changeCustomer jika ada, fallback ke user->id_no
                        $charField4 = $changeMember ? $user->first_name . ' ' . $user->last_name . ' (' . $user->id_no . ')' : ''; // Jika ada changeCustomer, gunakan user->id_no

                        // Data untuk API
                        $dpData = [
                            'customerNo' => $customerNo, // Menggunakan changeCustomer jika ada
                            'transDate' => now()->format('d/m/Y'),
                            'soNumber' => $nomorSO,
                            'poNumber' => $feeNumber->nomor,
                            'dpAmount' => round($feeNumber->total), // Membulatkan ke bilangan bulat terdekat
                            'branchName' => 'Jakarta',
                            'charField1' => 'baicircle.id',
                            'toAddress' => $user->home_addr, // Tetap gunakan alamat dari user asli
                            'status' => 'Draf',
                            'inclusiveTax' => true,
                            'isTaxable' => true,
                            'charField4' => $charField4, // Diisi dengan user->id_no jika ada changeCustomer
                            'taxType' => 'CTAS_DPP_NILAI_LAIN',
                            'forceCalculateTaxRate' => true,
                            'taxRate' => '12',
                        ];
                        // dd($dpData);
                        // Dispatch job
                        SendDownPaymentJob::dispatch(null, null, $dpData, null)->onConnection('sync');
                    }
                }

                if ($fieldName == 'dt_pengajuan') {
                    FeePaymentMadeModel::hitungPaymentMade($feenumberid);
                }

                SendNotifFeeJob::dispatch($feenumberid, $fieldName);
            }
            if ($fieldName == 'dt_acc' || $fieldName == 'dt_pengajuan') {
                FeeNumberModel::updateRekeningPengajuan();
            }
            //        LogController::writeLog(ValidatedPermission::UBAH_DATA_FEE, 'Fee Perpindahan Tahap  '.$status, $id);
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui.',
                'data' => $r
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
        // return response()->json([
        //     'data' => $r
        // ]);
    }

    public function status_set($status)
    {
        $mapStatus = [
            'pengajuan' => 'dt_pengajuan',
            //                'proses' => 'dt_proses',
            'acc' => 'dt_acc',
            'selesai' => 'dt_finish',
            'dp' => 'dt_dp'
        ];
        $stt = $mapStatus[$status];
        // dd($stt . ' - ' . $status);
        if ($stt == null) return \response()->json(['data' => "404"], 404);
        return $this->gantiStatus($stt, $status);
    }

    public function remove_status($status)
    {
        $id = \request('id');
        $mapStatus = [
            'pengajuan' => 'dt_pengajuan',
            'acc' => 'dt_acc',
            'selesai' => 'dt_finish',
            'dp' => 'dt_dp'
        ];

        $fieldName = $mapStatus[$status];
        if ($fieldName == null) abort(404);

        $r = 0;

        foreach ($id as $ii) {
            $ss = explode("|", $ii);
            $userid = $ss[0];
            $feenumberid = $ss[1];

            // Cek status fee_number_dp terlebih dahulu
            if ($fieldName === 'dt_dp') {
                $feeDP = FeeNumberDP::query()
                    ->where('fee_number_id', $feenumberid)
                    ->first();

                if ($feeDP && $feeDP->status === 'APPROVED') {
                    return response()->json([
                        'message' => 'Tidak dapat menghapus status DP karena sudah disetujui.',
                        'fee_number_id' => $feenumberid,
                        'dp_id' => $feeDP->dp_id,
                    ], 403);
                }
            }
            // Update FeeProfessionalModel
            $updateDataProfessional = [
                'admin_user_id' => session('admin')->id,
                'updated_at' => Carbon::now(),
                'harus_dibayar' => \DB::raw('total_pembayaran'),
                $fieldName => null
            ];

            // Jika status adalah dp, null-kan juga dt_finish
            if ($fieldName === 'dt_dp') {
                $updateDataProfessional['dt_finish'] = null;
            }

            $r += FeeProfessionalModel::query()
                ->where('member_user_id', $userid)
                ->where('fee_number_id', $feenumberid)
                ->whereNotNull($fieldName)
                ->update($updateDataProfessional);

            // Update FeeNumberModel
            $updateDataNumber = [
                'admin_user_id' => session('admin')?->id,
                'updated_at' => Carbon::now(),
                $fieldName => null
            ];

            // Jika status adalah dp, null-kan juga dt_finish
            if ($fieldName === 'dt_dp') {
                $updateDataNumber['dt_finish'] = null;
            }

            FeeNumberModel::query()
                ->where('id', $feenumberid)
                ->whereNotNull($fieldName)
                ->update($updateDataNumber);

            // Jika status adalah pengajuan, hapus juga pembayaran terkait
            if ($fieldName == 'dt_pengajuan') {
                FeePaymentMadeModel::removePaymentMade($feenumberid);
            }
            if ($fieldName === 'dt_dp') {
                if ($feeDP && $feeDP->dp_id) {
                    // Dispatch job untuk menghapus data di Accurate API
                    SendDownPaymentJob::dispatch(null, null, null, $feeDP->dp_id)->onConnection('sync');

                    // Log penghapusan dp_id
                    Log::info('Menghapus Down Payment melalui API', [
                        'dp_id' => $feeDP->dp_id,
                        'fee_number_id' => $feenumberid,
                    ]);
                }
            }
        }

        LogController::writeLog(ValidatedPermission::UBAH_DATA_FEE, 'Ganti status fee  ' . $status, $id);

        return response()->json([
            'data' => $r
        ]);
    }


    public function unduhCSV($status = '')
    {
        if ($status != 'disetujui') {
            abort(404);
        }

        $id = request('id');
        $ids = json_decode($id, true);

        return (new PengajuanFeeCSV($ids))->download('daftar-disetujui.csv', Excel::CSV, [
            'Content-type'  => 'text/csv'
        ]);
    }

    public function unduhXLS($status = '')
    {


        $f = $status == '' ? 'fee' : $status;
        $f = str_replace(['.', '..', '\\', '/', ':', "*", '&', "%", "$", "?"], '', $f) . '_' . date('YmdHi') . '.xlsx';
        $rfe = new ResumeFeeExport($status);
        if (count($rfe->sheets()) > 0) {
            return $rfe->download($f);
        }
        return abort(404);
    }



    public function report_outstanding()
    {
        return view('admin.fee.report_outstanding');
    }

    public function countTab($step = 0)
    {
        if ($step == 0) {
            return FeeProfessionalModel::resume()
                ->whereNull(['dt_pengajuan', 'dt_acc', 'dt_finish'])->get()->count();
        } else if ($step == 1) {
            return FeeProfessionalModel::resume()
                ->whereNotNull('dt_pengajuan')
                ->whereNull(['dt_acc', 'dt_finish'])->get()->count();
        } else if ($step == 2) {
            return FeeProfessionalModel::resume()
                ->whereNotNull('dt_acc')
                ->whereNull(['dt_finish', 'dt_dp'])->get()->count();
        } else if ($step == 3) {
            return FeeProfessionalModel::resume()
                ->whereNotNull('dt_finish')
                ->whereNull('dt_dp')->get()->count();
        }

        return FeeProfessionalModel::resume()
            ->whereNotNull('dt_dp')->get()->count();
    }

    public function sumTab($step = 0)
    {
        if ($step == 0) {
            return FeeProfessionalModel::resume()
                ->whereNull(['dt_pengajuan', 'dt_acc', 'dt_finish'])->get()->sum('total_pembayaran');
        } else if ($step == 1) {
            return FeeProfessionalModel::resume()
                ->whereNotNull('dt_pengajuan')
                ->whereNull(['dt_acc', 'dt_finish'])->get()->sum('total_pembayaran');
        } else if ($step == 2) {
            return FeeProfessionalModel::resume()
                ->whereNotNull('dt_acc')
                ->whereNull('dt_finish')->get()->sum('total_pembayaran');
        } else if ($step == 3) {
            return FeeProfessionalModel::resume()
                ->whereNotNull('dt_finish')
                ->whereNull('dt_dp')->get()->sum('total_pembayaran');
        }
        return FeeProfessionalModel::resume()
            ->whereNotNull('dt_dp')->get()->sum('total_pembayaran');
    }

    public function prosesDP($id)
    {
        $fee = FeeNumberModel::find($id);
        dd($fee);

        // if (!$fee) {
        //     return redirect()->back()->with('error', 'Fee tidak ditemukan.');
        // }

        // $fee->dp_processed = true;
        // $fee->save();

        // return redirect()->back()->with('success', 'Proses DP berhasil.');
    }
}
