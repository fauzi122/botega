
<?php

namespace App\Jobs;

use App\Library\APIAccurate;
use App\Models\FeeNumberModel;
use App\Models\FeeProfessionalModel;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncNoFakturFeeMemberJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $fee = FeeNumberModel::query()->whereNotNull([
                        'dt_pengajuan', 'dt_acc'
                    ])->whereNull([
                        'dt_finish', 'no_faktur'
                    ])->get();
        foreach ($fee as $f){
//            $r = $this->findFaktur($f->nomor);
            $r = $this->findFaktur($f->kode_merger);
            if($r != null){
                if($f->kode_merger == $r['charField1'] && $r['approvalStatus'] == 'APPROVED'){
                    $nofaktur = $r['number'];
                    FeeProfessionalModel::query()->where('fee_number_id', $f->id)
                        ->update([
                            'no_faktur' => $nofaktur,
                            'dt_finish' => Carbon::now(),
                        ]);
                    FeeNumberModel::query()->where('id', $f->id)
                        ->update([
                            'no_faktur' => $nofaktur,
                            'dt_finish' => Carbon::now(),
                        ]);
                    SendNotifEmailFeeHasPaidJob::dispatch($f->id);
                }
            }
        }
    }

    private function findfaktur($nomorFee){
        if ($nomorFee != null) {
            $r = new APIAccurate();
            $keyword = urlencode($nomorFee);
            $response = $r->get('/api/purchase-payment/list.do?fields=' . urlencode('id,number,charField1,approvalStatus') . '&sp.page=1&sp.sort=id|desc&filter.keywords.op=CONTAIN&filter.keywords.val[0]='.$keyword);
            if($response->status() != 200)return null;
            $json = $response->json();
            if(count($json['d']) <= 0 )return null;

            foreach($json['d'] as $item){
                if( strtolower($item['charField1']) == strtolower($nomorFee) ){
                    return $item;
                }
            }

            return $json['d'][0];
        }
    }
}