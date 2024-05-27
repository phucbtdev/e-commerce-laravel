<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;

class MyOrderPage extends Component
{
    #[Title('Order page')] 
    public function render()
    {
        return view('livewire.my-order-page');
    }
}