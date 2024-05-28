<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <div class="container mx-auto px-4">
        <h1 class="text-2xl font-semibold mb-4">Shopping Cart</h1>
        <div class="flex flex-col md:flex-row gap-4">
            <div class="md:w-3/4">
                <div class="bg-white overflow-x-auto rounded-lg shadow-md p-6 mb-4">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="text-left font-semibold">Product</th>
                                <th class="text-left font-semibold">Price</th>
                                <th class="text-left font-semibold">Quantity</th>
                                <th class="text-left font-semibold">Total</th>
                                <th class="text-left font-semibold">Remove</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($cart_items as $cart_item)
                                <tr>
                                    <td class="py-4">
                                        <div class="flex items-center">
                                            <img class="h-16 w-16 mr-4" src="{{url('storage', $cart_item['images'])}}" alt="Product image">
                                            <span class="font-semibold">{{$cart_item['name']}}</span>
                                        </div>
                                    </td>
                                    <td class="py-4">{{Number::currency($cart_item['unit_amount'])}}</td>
                                    <td class="py-4">
                                        <div class="flex items-center">
                                            <button wire:click='decrementQuantity({{$cart_item['product_id']}})' class="border rounded-md py-2 px-4 mr-2">-</button>
                                            <span class="text-center w-8">{{$cart_item['quantity']}}</span>
                                            <button wire:click='incrementQuantity({{$cart_item['product_id']}})' class="border rounded-md py-2 px-4 ml-2">+</button>
                                        </div>
                                    </td>
                                    <td class="py-4">{{Number::currency($cart_item['total_amount'])}}</td>
                                    <td><button wire:click='removeItem({{$cart_item['product_id']}})' class="bg-slate-300 border-2 border-slate-400 rounded-lg px-3 py-1 hover:bg-red-500 hover:text-white hover:border-red-700"><span wire:loading.remove wire:target='removeItem({{$cart_item['product_id']}})'>Remove</span ><span wire:loading wire:target='removeItem({{$cart_item['product_id']}})'>Removing ...</span></button>
                                    </td>
                                </tr>
                            @empty
                            <tr>
                                <td colspan="5"><h1 class="text-center text-3xl font-semibold">Cart is empty!</h1></td>
                            </tr>
                            @endforelse
                            
                            <!-- More product rows -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="md:w-1/4">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold mb-4">Summary</h2>
                    <div class="flex justify-between mb-2">
                        <span>Subtotal</span>
                        <span>{{Number::currency($grand_total)}}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span>Taxes</span>
                        <span>{{Number::currency(0)}}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span>Shipping</span>
                        <span>{{Number::currency(0)}}</span>
                    </div>
                    <hr class="my-2">
                    <div class="flex justify-between mb-2">
                        <span class="font-semibold">Total</span>
                        <span class="font-semibold">{{Number::currency($grand_total)}}</span>
                    </div>
                    @if ($cart_items)
                    <a href="/checkout" class="bg-blue-500 block text-center text-white py-2 px-4 rounded-lg mt-4 w-full">Checkout</a>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
