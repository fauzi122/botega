<?php

namespace App\Exports;

use App\Models\FeeNumberModel;
use App\Models\FeeProfessionalModel;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class FeeProfesionalRekapAll implements FromView, WithEvents, WithTitle
{
    private $status = '';
    private $data;
    private $periode;
    private $strPeriode;

    public function __xxxconstruct($status = '')
    {
        $this->status = $status;
        $v = FeeProfessionalModel::view();
        if($this->status == ''){
            $v = $v->whereNull(['dt_pengajuan',  'dt_acc', 'dt_finish']);

        }else if($this->status == 'pengajuan'){
            $v = $v->whereNotNull('dt_pengajuan')
                   ->whereNull([ 'dt_acc', 'dt_finish']);

        }else if($this->status == 'acc'){
            $v = $v->whereNotNull(['dt_pengajuan',  'dt_acc'])
                   ->whereNull('dt_finish');
        }else{
            $v = $v->whereNotNull(['dt_acc', 'dt_finish']);
        }
        $this->data = $v->groupBy(['member_user_id', 'periode'])
                         ->select([
                             'member_user_id', 'first_name', 'last_name', 'id_no', 'npwp',
                             \DB::raw('DATE_FORMAT( concat(periode,"-01"), "%b %y" ) as periode'),
                             'salesname',
                             \DB::raw("sum(dpp_amount) as dpp_amount"),
                             \DB::raw("sum(fee_amount) as fee_amount"),
                             \DB::raw("sum(pph_amount) as pph_amount"),
                             \DB::raw("sum(total_pembayaran) as total_pembayaran"),
                             'is_perusahaan',
                             'nama_bank', 'no_rekening', 'an_rekening'
                         ])->get();

    }

    public function __construct($status = '')
    {
        $this->status = $status;
        $v = FeeNumberModel::view();
        if($this->status == ''){
            $v = $v->whereNull(['dt_pengajuan',  'dt_acc', 'dt_finish']);

        }else if($this->status == 'pengajuan'){
            $v = $v->whereNotNull('dt_pengajuan')
                ->whereNull([ 'dt_acc', 'dt_finish']);

        }else if($this->status == 'acc'){
            $v = $v->whereNotNull(['dt_pengajuan',  'dt_acc'])
                ->whereNull('dt_finish');
        }else{
            $v = $v->whereNotNull(['dt_acc', 'dt_finish']);
        }
        $v->where('fee','>', 0);
        $this->data = $v->select([
                'member_user_id', 'first_name', 'last_name', 'id_no', 'npwp',
                'periode', 'dpp_amount', 'fee_amount', 'pph_amount', 'total_pembayaran',
                'is_perusahaan', 'payment_made', 'pph21', 'pph23',
                'nama_bank', 'no_rekening', 'an_rekening'
            ])->get();

    }

    public function title(): string
    {
        return 'SUMMARY FEE '. $this->strPeriode;
    }

    public function registerEvents(): array
    {
        $cellRange      = 'A6:M'.$this->data->count() + 7;

        return [
            AfterSheet::class => function(AfterSheet $event)use($cellRange){
                $style = $event->sheet->getStyle($cellRange);
                $style->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ])->getAlignment()->setWrapText(false);
                $event->sheet->getStyle('A6:M6')->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'FFC0C0CA', // Ganti dengan kode warna yang diinginkan
                        ],
                    ],
                ]);
                $event->sheet->autoSize();
            }
        ];
    }

    public function view(): View
    {
        return view('admin.export.fee_rekap_all', [
            'data' => $this->data,
            'periode' => $this->strPeriode
        ]);
    }


}
