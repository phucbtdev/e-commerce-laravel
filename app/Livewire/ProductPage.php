<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use Livewire\Attributes\Url;
use Livewire\Attributes\Title;
use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ProductPage extends Component
{
    use LivewireAlert;
    
    #[Title('Product page')]

    #[Url]
    public $search_categories = [];

    #[Url]
    public $search_brands = [];

    #[Url]
    public $is_stock;

    #[Url]
    public $on_sale;

    #[Url]
    public $price = 50000;

    #[Url]
    public $sort = 'latest';

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
        $products = Product::where('is_active', 1);

        if (!empty($this->search_categories)) {
            $products->whereIn('category_id', $this->search_categories);
        }

        
        if (!empty($this->search_brands)) {
            $products->whereIn('brand_id', $this->search_brands);
        }

        if ($this->is_stock) {
            $products->where('is_stock', $this->is_stock);
        }

        if ($this->on_sale) {
            $products->where('on_sale', $this->on_sale);
        }

        if ($this->price) {
            $products->whereBetween('price', [0 , $this->price]);
        }

        if ($this->sort == 'latest') {
            $products->latest();
        }

        if ($this->sort == 'price') {
            $products->orderBy('price');
        }


        return view('livewire.product-page',[
            'products' => $products->paginate(9),
            'brands' => Brand::where('is_active', 1)->get(['id','name','slug']),
            'categories' =>Category::where('is_active', 1)->get(['id','name','slug'])
        ]);
    }
}