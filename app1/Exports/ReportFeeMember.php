<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ReportFeeMember implements FromView, WithEvents, WithTitle
{
    use Exportable;
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
    }


    public function registerEvents(): array
    {
        $cellRange      = 'A5:G'.$this->data['data']->count() + 5;

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
                $event->sheet->getStyle('A5:G5')->applyFromArray([
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

    public function title(): string
    {
        // TODO: Implement title() method.
        return 'Fee Per Nama Professional';
    }

    public function view(): View
    {
        return view('admin.report.xls.fee-member', $this->data);
    }
}
