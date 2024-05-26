<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;

class SuccessPage extends Component
{
    #[Title('Success page')] 
    public function render()
    {
        return view('livewire.success-page');
    }
}