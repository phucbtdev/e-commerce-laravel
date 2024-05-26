<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use Livewire\Attributes\Url;
use Livewire\Attributes\Title;

class ProductPage extends Component
{
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
    public $price;
    
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

        return view('livewire.product-page',[
            'products' => $products->paginate(9),
            'brands' => Brand::where('is_active', 1)->get(['id','name','slug']),
            'categories' =>Category::where('is_active', 1)->get(['id','name','slug'])
        ]);
    }
}