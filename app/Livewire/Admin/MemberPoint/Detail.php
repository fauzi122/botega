<?php

namespace App\Livewire\Admin\MemberPoint;

use Livewire\Component;
use App\Models\MemberPointModel;

class Detail extends Component
{
    public $memberPoints = [];

    public function viewDetails($userId)
    {
        // Fetch member points based on user ID
        $this->memberPoints = MemberPointModel::where('user_id', $userId)
            ->with('transaction')
            ->get();

        // Emit the event to open the modal
        $this->dispatchBrowserEvent('showDetailModal');
    }

    public function render()
    {
        return view('livewire.admin.member-point.detail');
    }
}
