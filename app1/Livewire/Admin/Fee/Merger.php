<?php

namespace App\Livewire\Admin\Fee;

use App\Models\FeeNumberModel;
use App\Models\FeeProfessionalModel;
use Livewire\Component;

class Merger extends Component
{
    public $kode_merger;
    public $id = [];
    public $jmlmerger = 0;

    public function render()
    {
        return view('livewire.admin.fee.merger');
    }

    public function setId($id){
        $this->id = $id;
        if(is_array($this->id)){
            $this->jmlmerger = count($this->id);
        }
    }

    public function simpan(){
        $v = $this->validate([
            'kode_merger' => 'required'
        ],[
            'kode_merger.required' => 'Kode Merger harus diisi'
        ]);

        foreach ($this->id as $key => $value) {
            [$member_user_id, $fee_number_id] = explode("|", $value);
            FeeProfessionalModel::query()
                ->where([
                    "fee_number_id" => $fee_number_id,
                    "member_user_id" => $member_user_id
                ])
                ->update(["kode_merger"=>$this->kode_merger]);
            FeeNumberModel::query()->where([
                "id" => $fee_number_id,
                "member_user_id" => $member_user_id
            ])->update(['kode_merger'=>$this->kode_merger]);

            $this->dispatch('refreshData');
        }

    }
}
