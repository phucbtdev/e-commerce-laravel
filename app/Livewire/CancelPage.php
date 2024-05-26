<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;

class CancelPage extends Component
{
    #[Title('Cancel page')] 
    public function render()
    {
        return view('livewire.cancel-page');
    }
}