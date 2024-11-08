<?php

namespace App\Jobs;

use App\Exports\PDFRekapFee;
use App\Models\FeeNumberModel;
use App\Models\FeePaymentMadeModel;
use App\Models\FeeProfessionalModel;
use App\Models\UserModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Message;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class SendNotifEmailFeeHasPaidJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $feeNumberID;
    /**
     * Create a new job instance.
     */
    public function __construct($feeNumberID=0)
    {
        $this->feeNumberID = $feeNumberID;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $feenum = FeeNumberModel::query()->where('id', $this->feeNumberID)->first();
        $member = UserModel::query()->where('id', $feenum->member_user_id)->first();
        if($member == null)return;

        $rkp = new PDFRekapFee($this->feeNumberID);
        $pdffile = $rkp->getPDF();

        \Mail::send('admin.email.notif-fee-paid', [
            'fee' => $feenum,
            'member' => $member,
            'paid' => FeePaymentMadeModel::view()->where('fee_number_id', $this->feeNumberID)->get(),
            'feepro' => FeeProfessionalModel::view()->where('fee_number_id', $this->feeNumberID)->get()
        ], function(Message $msg)use($feenum, $member, $pdffile){


            $msg->to($member->email, ($member?->first_name ?? '') . ' ' . ($member?->last_name ?? '') );
            $msg->subject('BOTTEGA: Pemberitahuan Fee Telah Ditransfer');
            $msg->attachData( $pdffile, 'lampiran-pembayaran.pdf', ['mime'=>'application/pdf'] );
        });
    }
}
