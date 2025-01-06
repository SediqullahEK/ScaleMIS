<?php

namespace App\Livewire;

use Livewire\Component;

class Test extends Component
{
    public function save(){
        dd('ok');
    }

    public function render()
    {
        return view('livewire.test');
    }
}
