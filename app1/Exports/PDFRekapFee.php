<?php

namespace App\Exports;

use App\Models\FeeNumberModel;
use App\Models\FeePaymentMadeModel;
use App\Models\FeeProfessionalModel;
use App\Models\UserModel;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class PDFRekapFee implements FromView
{

    use Exportable;
    private $feeNumberID;

    public function __construct($feeNumberID)
    {
        $this->feeNumberID = $feeNumberID;
    }

    public function view(): View
    {
        $feenum = FeeNumberModel::query()->where('id', $this->feeNumberID)->first();
        $member = UserModel::query()->where('id', $feenum->member_user_id)->first();

        return \view('admin.export.pdfrekapfee',[
           'fee' => $feenum,
           'member' => $member,
           'paid' => FeePaymentMadeModel::view()->where('fee_number_id', $this->feeNumberID)->get(),
           'feepro' => FeeProfessionalModel::view()->where('fee_number_id', $this->feeNumberID)->get()
       ]);
    }

    public function getPDF(){
            $dompdf = new Dompdf();

            // Buat opsi dompdf
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);

            // Gunakan opsi
            $dompdf->setOptions($options);

            $feenum = FeeNumberModel::query()->where('id', $this->feeNumberID)->first();
            $member = UserModel::query()->where('id', $feenum->member_user_id)->first();

            $html = \view('admin.export.pdfrekapfee',[
                'fee' => $feenum,
                'member' => $member,
                'paid' => FeePaymentMadeModel::view()->where('fee_number_id', $this->feeNumberID)->get(),
                'feepro' => FeeProfessionalModel::view()->where('fee_number_id', $this->feeNumberID)->get()
            ])->render();

            // Render HTML ke PDF
            $dompdf->loadHtml($html);

            // Render PDF (pengaturan ukuran kertas dan orientasi)
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();

            return $dompdf->output();

    }
}
