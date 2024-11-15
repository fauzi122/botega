<?php

namespace App\Livewire\Frontend;

use App\Models\YoutubeModel;
use Livewire\Component;

class Youtube extends Component
{
    public function render()
    {
        $youtube = YoutubeModel::query()->orderBy('id','desc')->get();
        $data = [
            'youtube'=>$youtube
        ];
        return view('livewire.frontend.youtube',$data);
    }
}
