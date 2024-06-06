<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\OrderItem;
use Livewire\Attributes\Title;
use Livewire\Component;

class OrderDetailPage extends Component
{
    #[Title('Order detail page')]

    public $order_id;

    public function mount($order_id)
    {
        $this->order_id = $order_id;
    }

    public function render()
    {
        $order_item = OrderItem::with('product')->where('order_id', $this->order_id)->get();
        $order = Order::with('address')->where('id', $this->order_id)->first();
        
        return view('livewire.order-detail-page', [
            'order_item' => $order_item,
            'order' => $order,
        ]);
    }
}