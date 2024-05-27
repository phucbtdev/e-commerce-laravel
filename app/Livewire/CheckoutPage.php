<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;

class CheckoutPage extends Component
{
    #[Title('Checkout page')] 
    public function render()
    {
        return view('livewire.checkout-page');
    }
}