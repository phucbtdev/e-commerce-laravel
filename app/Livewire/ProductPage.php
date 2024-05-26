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
    public ?string $brand;
    public function render()
    {
        $products = Product::where('is_active', 1);

        return view('livewire.product-page',[
            'products' => $products->paginate(2),
            'brands' => Brand::where('is_active', 1)->get(['id','name','slug']),
            'categories' =>Category::where('is_active', 1)->get(['id','name','slug'])
        ]);
    }
}