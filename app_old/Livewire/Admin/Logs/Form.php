<?php

namespace App\Livewire\Admin\Logs;

use App\Models\LevelMemberModel;
use App\Models\LogsModel;
use App\Models\RequestUpdateModel;
use Livewire\Component;

class Form extends Component
{
    public $actions;
    public $payload;
    public $user_id;
    public $jsonPayload;

    public function loadData($id){
        $logs = LogsModel::query()->where('id', $id)->first();
        $this->actions = $logs?->actions ?? '';
        $this->payload = $logs?->payload ?? '';
        $this->user_id = $logs?->user_id ?? '';
        $this->jsonPayload = json_decode($this->payload);
    }

    public function render()
    {
        return view('livewire.admin.logs.form');
    }
}
