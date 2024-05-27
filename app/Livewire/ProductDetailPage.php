<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\Title;
use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ProductDetailPage extends Component
{
    use LivewireAlert;
    
    #[Title('Product detail page')]

    public $slug;

    public $quantity = 1;

    public function mount($slug){
        $this->slug = $slug;
    }

    public function incrementQuantity(){
        $this->quantity++;
    }

    public function decrementQuantity(){
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart($product_id){
        $total_count = CartManagement::addItemToCart($product_id);
        $this->dispatch('update-cart-count', total_count: $total_count)->to(Navbar::class);

        $this->alert('success', 'Product added to the cart successfully!', [
            'position' => 'top-end',
            'timer' => '3000',
            'toast' => true,
            'timerProgressBar' => true,
        ]); 
    }
    
    public function render()
    {
        return view('livewire.product-detail-page',[
            'product' => Product::where('slug', $this->slug)->first()
        ]);
    }
}