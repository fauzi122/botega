<?php

namespace App\Livewire;

use Livewire\Component;

class Selectchoosen extends Component
{
    public $name;
    public $model;

    public function mount($name = '', $model = null){
        $this->name = $name;
        $this->model = $model;
    }
    
    public function render()
    {
        return view('livewire.selectchoosen');
    }
}
