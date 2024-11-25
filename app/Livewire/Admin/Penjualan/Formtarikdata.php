<?php

namespace App\Livewire\Admin\Penjualan;

use App\Jobs\SyncPenjualanJob;
use Carbon\Carbon;
use Livewire\Component;

class Formtarikdata extends Component
{
    public $tgl1;
    public $tgl2;
    public $nomor_so;

    public function render()
    {
        return view('livewire.admin.penjualan.formtarikdata');
    }

    public function proses()
    {
        // dd($this->nomor_so);
        if (strlen($this->tgl1 ?? '') > 5) {
            $tgl1 = Carbon::parse($this->tgl1)->format('d/m/Y');
            SyncPenjualanJob::dispatch($tgl1, false, '', $tgl1);
        }

        if (strlen($this->nomor_so ?? '')  > 5) {
            // dd("1");
            session()->flash('error', 'singkron so ' . $this->nomor_so);
            SyncPenjualanJob::dispatch('', false, $this->nomor_so, null);
        }
        // dd($this->nomor_so);

        $this->dispatch('refresh');
    }
}
