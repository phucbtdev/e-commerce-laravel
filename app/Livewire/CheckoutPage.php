<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Helpers\Momo;
use App\Mail\OrderPlace;
use App\Models\Address;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Title;
use Livewire\Component;

class CheckoutPage extends Component
{
    #[Title('Checkout page')]

    public $first_name;
    public $last_name;
    public $phone;
    public $street_address;
    public $city;
    public $state;
    public $zip_code;
    public $payment_method;

    public function mount()
    {
        $cart_item = CartManagement::getCartItemsFromCookie();
        if (count($cart_item) == 0) {
            return redirect('/products');
        }
    }

    public function placeOrder()
    {
        $this->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'street_address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip_code' => 'required',
            'payment_method' => 'required',
        ]);

        $cart_items = CartManagement::getCartItemsFromCookie();

        $line_items = [];

        foreach ($cart_items as $item) {
            $line_items[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'unit_ amount' => $item['unit_amount'] * 100,
                    'product_data' => [
                        'name' => $item['name'],
                    ],
                ],
                'quantity' => $item['quantity'],
            ];
        }

        $order = new Order();
        $order->user_id = auth()->user()->id;
        $order->grand_total = CartManagement::calculateGrandTotal($cart_items);
        $order->payment_method = $this->payment_method;
        $order->payment_status = 'pending';
        $order->status = 'new';
        $order->currency = 'usd';
        $order->shipping_amount = 0;
        $order->shipping_method = 'free';
        $order->notes = 'Order placed by ' . auth()->user()->name;

        $address = new Address();
        $address->first_name = $this->first_name;
        $address->last_name = $this->last_name;
        $address->phone = $this->phone;
        $address->street_address = $this->street_address;
        $address->city = $this->city;
        $address->state = $this->state;
        $address->zipcode = $this->zip_code;

        $redirect_url = '';

        if ($this->payment_method === 'momo') {
            $response = Momo::payment();
            // $sessionCheckOut = Session::create([
            //     'payment_method_types' => ['card'],
            //     'customer_email' => auth()->user()->email,
            //     'lines_items' => $line_items,
            //     'success_url' => route('success') . '?session_id={CHECKOUT_SESSION_ID}',
            //     'cancel_url' => route('cancel'),
            // ]);

            if ($response['errorCode'] == 0) {
                $redirect_url = $response['payUrl'];
            }
        } else if ($this->payment_method === 'vnpay') {

        } else {
            $redirect_url = 'success';
        }

        $order->save();
        $address->order_id = $order->id;
        $address->save();
        $order->items()->createMany($cart_items);

        CartManagement::clearCartItems();
        Mail::to(auth()->user())->send(new OrderPlace($order));

        return redirect($redirect_url);
    }

    public function render()
    {
        $cart_items = CartManagement::getCartItemsFromCookie();
        $grand_total = CartManagement::calculateGrandTotal($cart_items);
        return view('livewire.checkout-page', [
            'cart_items' => $cart_items,
            'grand_total' => $grand_total,
        ]);
    }
}