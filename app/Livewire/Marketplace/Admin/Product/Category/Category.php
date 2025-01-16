<?php

namespace App\Livewire\Marketplace\Admin\Product\Category;

use Livewire\Component;
use App\Models\Marketplace\ProductCategory;

class Category extends Component
{
    public $categories;

    public function mount()
    {
        $this->categories   = ProductCategory::with('sub')->get();
    }

    public function render()
    {
        return view('livewire.marketplace.admin.product.category.category');
    }
}
