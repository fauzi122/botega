<?php

namespace App\Livewire\Frontend;

use App\Models\SliderModel;
use Livewire\Component;

class Slider extends Component
{
    public function render()
    {
        $sliders = SliderModel::query()->limit(3)->orderBy('order', 'asc')->get();
        $data = [
            'sliders'=>$sliders
        ];
        return view('livewire.frontend.slider', $data);
    }
}
