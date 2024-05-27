<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;

class OrderDetailPage extends Component
{
    #[Title('Order detail page')] 
    public function render()
    {
        return view('livewire.order-detail-page');
    }
}