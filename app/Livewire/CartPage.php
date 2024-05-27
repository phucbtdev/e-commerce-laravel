<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;

class CartPage extends Component
{
    #[Title('Cart page')] 
    public function render()
    {
        return view('livewire.cart-page');
    }
}