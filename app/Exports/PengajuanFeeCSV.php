<?php

namespace App\Exports;

use App\Models\FeeNumberModel;
use App\Models\SettingsModel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class PengajuanFeeCSV implements FromView, WithCustomCsvSettings
{
    use Exportable;

    private $fees;

    public function __construct($ids = [])
    {
        FeeNumberModel::updateRekeningPengajuan();

        if ($ids == null || count($ids) <= 0) {
            $this->fees = FeeNumberModel::view()->whereNotNull([
                'dt_acc'
            ])->whereNull([
                'dt_finish'
            ])->get();
        } else {

            $this->fees = FeeNumberModel::view()
                ->whereIn('id', $ids)
                ->whereNotNull([
                    'dt_acc'
                ])->whereNull([
                    'dt_finish'
                ])->get();
        }
    }


    public function getCsvSettings(): array
    {
        return [
            'enclosure' => ''
        ];
    }

    public function view(): View
    {
        $totalamount = 0;
        foreach ($this->fees as $f) {
            $totalamount += doubleval($f?->total ?? 0);
        }

        return \view('admin.export.feecsv', [
            'fees' => $this->fees,
            'totalbaris' => $this->fees->count(),
            'totalamount' =>  $totalamount,
            'rek_debt' => SettingsModel::get('REK_DEBT', '1410018824722'),
            'bank_debt' => SettingsModel::get('BANK_DEBT', 'MANDIRI'),
            'kode_bank_debt' => SettingsModel::get('KODE_BANK_DEBT', 'BMRIIDJA'),
            'email_pic' => SettingsModel::get('EMAIL_PIC', 'bai.hendroith@gmail.com'),
        ]);
    }
}
