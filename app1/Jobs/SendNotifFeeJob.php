<?php

namespace App\Jobs;

use App\Exports\PDFRekapFee;
use App\Http\Controllers\Admin\LogController;
use App\Models\FeeNumberModel;
use App\Models\UserModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Message;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SendNotifFeeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $ideFee;
    private $fieldname;
    /**
     * Create a new job instance.
     */
    public function __construct($idfee, $fieldname)
    {
        $this->ideFee = $idfee;
        $this->fieldname = $fieldname;
    }

    private function statusName($fieldname){
        $map = [
            'dt_pengajuan' => 'Pengajuan',
            'dt_acc' => 'Disetujui',
            'dt_finish' => 'Selesai'
        ];
        return $map[$fieldname] ?? '';
    }


    private function createMessageNotif(){
        $feenum = FeeNumberModel::query()->where('id', $this->ideFee)->first();
        if($feenum == null)return;

        $member = UserModel::query()->where('id', $feenum->member_user_id)->first();
        $status = $this->statusName($this->fieldname);
        $fieldName = $this->fieldname;
        $pesan = "Status ".ucfirst($status).' = ' . $feenum->$fieldName. ' Fee Professional dengan nomor '.$feenum->nomor. ' sudah tahap '.$status;
        LogController::writeLog('Pemindahan Status Fee', $pesan, $this->ideFee, 0, $feenum->member_user_id);

        if( ($member->email ?? '') == '' ){

        }else {
            Mail::send('admin.email.notif-fee', [
                'message' => $pesan,
                'member' => $member,
                'fee' => $feenum,
                'status' => $status
            ], function (Message $m) use ($member, $status, $feenum) {
                $m->to($member->email);
                $m->subject('Notifikasi Perpindahan Tahapan Pengajuan Fee');
                if($status == 'Disetujui'){
                    $pkf = new PDFRekapFee($feenum->id);
                    $m->attachData( $pkf->getPDF(), 'rincian-pembayaran.pdf', ['mime'=>'application/pdf'] );
                }
            });
        }

        return $pesan;
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        FeeNumberModel::rekapNilaiFee($this->ideFee);
        FeeNumberModel::updateRekeningPengajuan();
        $this->createMessageNotif();

    }
}
