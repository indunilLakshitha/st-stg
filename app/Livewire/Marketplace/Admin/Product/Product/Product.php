<?php

namespace App\Livewire\Marketplace\Admin\Product\Product;

use App\Models\Marketplace\ProductCategory;
use Livewire\Component;

class Product extends Component
{
    public $categories;

    public function mount()
    {
        $this->categories = ProductCategory::where('parent_id', NULL)->get();
    }
    public function render()
    {
        return view('livewire.marketplace.admin.product.product.product');
    }
}
