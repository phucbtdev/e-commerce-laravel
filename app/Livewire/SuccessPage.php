<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Attributes\Title;
use Livewire\Component;

class SuccessPage extends Component
{
    #[Title('Success page')]

    public function render()
    {
        $order_latest = Order::with('address')->latest()->first();

        return view('livewire.success-page', [
            'order_latest' => $order_latest,
        ]);
    }
}