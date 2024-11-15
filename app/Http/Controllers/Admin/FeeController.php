<?php

namespace App\Http\Controllers\Admin;

use App\Exports\FeeProfesionalRekapAll;
use App\Exports\FeeProfessionalPerUser;
use App\Exports\PDFRekapFee;
use App\Exports\PengajuanFeeCSV;
use App\Exports\ResumeFeeExport;
use App\Http\Controllers\Controller;
use App\Jobs\SendNotifFeeJob;
use App\Library\ValidatedPermission;
use App\Models\ClaimItemTransactionModel;
use App\Models\DetailDeliveryOrderModel;
use App\Models\DetailTransactionModel;
use App\Models\FeeNumberModel;
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
                ->whereNull('dt_finish')
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

        if (!ValidatedPermission::authorize(ValidatedPermission::UBAH_DATA_FEE)) {
            return;
        }

        $id = \request('id');
        $r = false;
        foreach ($id as $ii) {
            $ss = explode("|", $ii);
            $userid = $ss[0];
            $feenumberid = $ss[1];
            $data = [
                'admin_user_id' => session('admin')?->id,
                'updated_at' => Carbon::now(),
                $fieldName => Carbon::now()
            ];

            if ($fieldName == 'dt_finish') {
                $data['harus_dibayar'] = 0;
            } else {
                $data['harus_dibayar'] = \DB::raw('total_pembayaran');
            }

            $r = FeeProfessionalModel::query()
                ->where('member_user_id', $userid)
                ->where('fee_number_id', $feenumberid)
                ->whereNull($fieldName)
                ->update($data);

            FeeNumberModel::query()
                ->where('id', $feenumberid)
                ->whereNull($fieldName)
                ->update([
                    'admin_user_id' => session('admin')?->id,
                    $fieldName => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
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
            'data' => $r
        ]);
    }

    public function status_set($status)
    {
        $mapStatus = [
            'pengajuan' => 'dt_pengajuan',
            //                'proses' => 'dt_proses',
            'acc' => 'dt_acc',
            'selesai' => 'dt_finish'
        ];
        $stt = $mapStatus[$status];
        dd($stt . ' - ' . $status);
        if ($stt == null) return \response()->json(['data' => "404"], 404);
        return $this->gantiStatus($stt, $status);
    }

    public function remove_status($status)
    {
        $id = \request('id');
        $mapStatus = [
            'pengajuan' => 'dt_pengajuan',
            //            'proses' => 'dt_proses',
            'acc' => 'dt_acc',
            'selesai' => 'dt_finish'
        ];

        $fieldName = $mapStatus[$status];
        if ($fieldName == null) abort(404);

        $r = 0;

        foreach ($id as $ii) {
            $ss = explode("|", $ii);
            $userid = $ss[0];
            $feenumberid = $ss[1];
            $r += FeeProfessionalModel::query()
                ->where('member_user_id', $userid)
                ->where('fee_number_id', $feenumberid)
                ->whereNotNull($fieldName)
                ->update([
                    'admin_user_id' => session('admin')->id,
                    'updated_at' => Carbon::now(),
                    'harus_dibayar' => \DB::raw('total_pembayaran'),
                    $fieldName => null
                ]);

            FeeNumberModel::query()
                ->where('id', $feenumberid)
                ->whereNotNull($fieldName)
                ->update([
                    'admin_user_id' => session('admin')?->id,
                    $fieldName => null,
                    'updated_at' => Carbon::now()
                ]);
            if ($fieldName == 'dt_pengajuan') {
                FeePaymentMadeModel::removePaymentMade($feenumberid);
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
                ->whereNull('dt_finish')->get()->count();
        }
        return FeeProfessionalModel::resume()
            ->whereNotNull('dt_finish')->get()->count();
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
        }
        return FeeProfessionalModel::resume()
            ->whereNotNull('dt_finish')->get()->sum('total_pembayaran');
    }
}
