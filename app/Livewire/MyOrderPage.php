<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Attributes\Title;
use Livewire\Component;

class MyOrderPage extends Component
{
    #[Title('Order page')]
    public function render()
    {
        $orders = Order::where('user_id', auth()->user()->id)->latest()->paginate(10);
        return view('livewire.my-order-page', [
            'orders' => $orders,
        ]);
    }
}
