<?php

namespace App\Exports;

use App\Models\FeePaymentMadeModel;
use App\Models\FeeProfessionalModel;
use App\Models\SummaryFeeProfessionalModel;
use App\Models\UserModel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

abstract  class ModeData{
    const SEBELUM_DIAJUKAN = '';
    const PENGAJUAN = 'pengajuan';
    const PROSES = 'proses';
    const ACC = 'acc';
    const FINISH = 'finish';
}
class FeeProfessionalPerUser implements FromView, WithTitle, WithEvents
{

    private $summary;
    private $user;
    private $detail;
    private $paymentMade;

    public function __construct( $summary   )
    {
        $this->summary = $summary;
        $this->user = UserModel::find($this->summary->member_user_id);
        $this->detail = FeeProfessionalModel::view()
            ->where('fee_number_id', $this->summary->fee_number_id)->get();
        $this->paymentMade = FeePaymentMadeModel::query()->where('fee_number_id', $this->summary->fee_number_id)->get();
    }

    public function view(): View
    {
        return view('admin.export.feereport',[
            'fee_number' => $this->summary->nomor,
            'user' => $this->user,
            'sum' => $this->detail,
            'periode' => $this->summary->periode,
            'paid' => $this->paymentMade
        ]);
    }


    public function title(): string
    {
        return $this->summary->first_name.' ' . $this->summary->last_name . '('.$this->summary->periode.')';
    }

    public function registerEvents(): array
    {
        $cellRange      = 'A6:P'.($this->detail->count() + $this->paymentMade->count() + 8);

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
                $event->sheet->getStyle('A6:P6')->applyFromArray([
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
}
