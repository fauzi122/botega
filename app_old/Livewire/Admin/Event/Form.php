<?php

namespace App\Livewire\Admin\Event;

use App\Http\Controllers\Admin\LogController;
use App\Jobs\SendEmailKonfirmasi;
use App\Library\ValidatedPermission;
use App\Models\EventsModel;
use App\Models\LevelMemberModel;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Form extends Component
{
    public $judul;
    public $start;
    public $end;
    public $descriptions;
    public $publish;
    public $member_id = [];

    public $editform = false;
    public $lm;

    public function edit($id)
    {
        if (!ValidatedPermission::authorize(ValidatedPermission::LIHAT_DATA_EVENT)) {
            return;
        }

        $this->lm = EventsModel::query()->find($id);
        $this->editform = $this->lm != null;
        $this->judul = $this->lm?->judul ?? null;
        $this->start = $this->lm?->start ?? null;
        $this->end = $this->lm?->end ?? null;
        $this->descriptions = $this->lm?->descriptions ?? '';
        $this->publish = $this->lm?->publish ?? 1;
        $this->member_id = json_decode($this->lm?->member_id ?? '[]', true);
    }

    public function newForm()
    {
        $this->reset(['judul', 'start', 'end', 'descriptions', 'publish', 'editform', 'lm']);
        $this->member_id = [];
    }

    public function toggleAktif($id)
    {
        if (!ValidatedPermission::authorize(ValidatedPermission::UBAH_DATA_EVENT)) {
            return;
        }

        EventsModel::where('id', $id)->update(['publish' => \DB::raw('not publish')]);
    }

    private function validasi()
    {
        return $this->validate(['judul' => 'required|min:3', 'start' => 'required|date', 'end' => 'required|date|after:start', 'descriptions' => 'required|min:5', 'publish' => 'required', 'member_id' => 'required|array',], ['judul' => 'Judul event harus diisikan', 'start.required' => 'Kolom Start harus diisi', 'start.date' => 'Kolom Start harus berisi tanggal', 'end.required' => 'Kolom End harus diisi', 'end.date' => 'Kolom End harus berisi tanggal', 'end.after' => 'Kolom End harus setelah Start', 'descriptions.required' => 'Kolom Descriptions harus diisi', 'descriptions.min' => 'Kolom Descriptions minimal 5 karakter', 'publish.required' => 'Kolom Publish harus diisi', 'member_id.required' => 'Pilih minimal satu member',]);
    }

    public function save()
    {
        if ($this->editform) {
            $this->update();
        } else {
            $this->store();
        }
    }

    public function store()
    {
        if (!ValidatedPermission::authorize(ValidatedPermission::TAMBAH_DATA_EVENT)) {
            return;
        }

        $v = $this->validasi();
        $v['created_at'] = Carbon::now();
        $v['member_id'] = json_encode($this->member_id);

        try {
            // Menggunakan insertGetId untuk mendapatkan ID event yang baru saja dimasukkan
            $eventId = EventsModel::query()->insertGetId($v);

            // Mengambil event berdasarkan ID
            $event = EventsModel::find($eventId);

            session()->flash('success', 'Data berhasil di simpan');
            $this->newForm();
            $this->dispatch('refresh');
            SendEmailKonfirmasi::dispatch($event->member_id, $event->id);

            // Mencatat log
            LogController::writeLog(ValidatedPermission::TAMBAH_DATA_EVENT, 'Menambah Event', $v);

        } catch (Exception $e) {
            session()->flash('error', 'Data Gagal disimpan' . $e->getMessage());
        }
    }


    public function update()
    {
        if (!ValidatedPermission::authorize(ValidatedPermission::UBAH_DATA_EVENT)) {
            return;
        }

        $v = $this->validasi();
        $v['updated_at'] = Carbon::now();
        $v['member_id'] = json_encode($this->member_id);
        try {
            EventsModel::query()->where('id', $this->lm->id)->update($v);
            session()->flash('success', 'Data berhasil diubah');
            $this->dispatch('refresh');
            LogController::writeLog(ValidatedPermission::UBAH_DATA_EVENT, 'Merubah Event yang telah ada', $v);
        } catch (Exception $e) {
            session()->flash('error', 'Data Gagal diubah' . $e->getMessage());
        }
    }

    public function render()
    {
        $member = LevelMemberModel::query()->orderBy('id', 'asc')->get();
        return view('livewire.admin.event.form', compact('member'));
    }
}
