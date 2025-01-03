<?php

namespace App\Livewire\Admin\Fee;

use App\Models\DetailTransactionModel;
use App\Models\FeeNumberModel;
use App\Models\FeePaymentMadeModel;
use App\Models\FeeProfessionalModel;
use App\Models\UserModel;
use Livewire\Component;

class Detailfee extends Component
{
    public $fee_professional;
    public $member;

    public $namamemberlengkap;
    public $member_user_id;
    public $periode;
    public $fee_number_id;
    public $feenumber;
    public $fee_payment_made;

    public function render()
    {
        $this->fee_professional = FeeProfessionalModel::view()
            ->where('fee_number_id', $this->fee_number_id)->get();
        if ($this->fee_professional->count() >= 1) {
            $f1 = $this->fee_professional[0];
            $this->fee_payment_made = FeePaymentMadeModel::query()->where('fee_number_id', $f1->fee_number_id)->get();
            $this->feenumber = FeeNumberModel::query()->where("id", $this->fee_number_id)->first();
            $this->member = UserModel::where('user_type', 'member')
                ->where('id', $f1?->member_user_id)->first();
            $this->namamemberlengkap = $this->member?->first_name . ' ' . $this->member?->last_name . ' (' . $this->member?->id_no . ')';
        }
        // dd($this->fee_professional);
        return view('livewire.admin.fee.detailfee');
    }
}
