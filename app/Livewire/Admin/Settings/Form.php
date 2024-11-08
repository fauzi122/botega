<?php

namespace App\Livewire\Admin\Settings;

use App\Models\BankModel;
use App\Models\SettingsModel;
use Livewire\Component;

class Form extends Component
{
    public $banks;
    public $rek_debt;
    public $bank_id;
    public $email_pic;

    public function mount(){
        $this->bank_id = SettingsModel::get('BANK_ID');
        $this->rek_debt = SettingsModel::get('REK_DEBT');
        $this->email_pic = SettingsModel::get('EMAIL_PIC');
    }

    public function save(){
        $bank = BankModel::query()->where('id', $this->bank_id)->first();
        $kodebank = '';
        $namabank = '';
        if($bank != null){
            $kodebank = $bank->kode_bank;
            $namabank = $bank->name;
        }

        $v = [
            ['keyname' => 'REK_DEBT', 'keyvalue'=>$this->rek_debt],
            ['keyname' => 'BANK_DEBT', 'keyvalue'=>$namabank],
            ['keyname' => 'BANK_ID', 'keyvalue'=>$this->bank_id],
            ['keyname' => 'EMAIL_PIC', 'keyvalue'=>$this->email_pic],
            ['keyname' => 'KODE_BANK_DEBT', 'keyvalue'=>$kodebank],
        ];

        try {
            foreach ($v as $dt) {
                SettingsModel::set($dt['keyname'], $dt['keyvalue']);
            }

            session()->flash('success', 'Data pengaturan berhasil direkam');
        }catch (\Exception $e){
            session()->flash('error', 'Data pengaturan gagal  direkam');
        }
    }

    public function render()
    {
        $this->banks = BankModel::query()->get();
        return view('livewire.admin.settings.form');
    }
}
