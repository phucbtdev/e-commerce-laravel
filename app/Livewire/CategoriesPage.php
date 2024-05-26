<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;
use Livewire\Attributes\Title;

class CategoriesPage extends Component
{
    #[Title('Categories page')] 
    public function render()
    {
        return view('livewire.categories-page',[
            'categories' => Category::where('is_active', 1)->get(),
        ]);
    }
}