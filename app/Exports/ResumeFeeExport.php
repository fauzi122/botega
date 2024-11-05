<?php

namespace App\Exports;

use App\Models\FeeNumberModel;
use App\Models\FeeProfessionalModel;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ResumeFeeExport implements WithMultipleSheets
{
    use Exportable;
    private $mode;


    public function __construct($mode)
    {
        $this->mode = $mode;
    }

    public function sheets(): array
    {
        set_time_limit(300);
        FeeNumberModel::fixData();
        $r = FeeProfessionalModel::view();

        if($this->mode == ''){
            $r = $r->whereNull('dt_pengajuan');
        }else if($this->mode == 'pengajuan'){
            $r = $r->whereNotNull('dt_pengajuan')->whereNull('dt_acc');
        }else if($this->mode == 'acc') {
            $r = $r->whereNotNull('dt_acc')->whereNull('dt_finish');
        }else{
            $r = $r->whereNotNull('dt_finish');
        }

        $sheet = [];
        $sheet[] = new FeeProfesionalRekapAll($this->mode);

        $data = $r->groupBy(['fee_number_id'])->get();
        foreach($data as $d){
            $sheet[] = new FeeProfessionalPerUser($d);
        }

        return $sheet;
    }
}
