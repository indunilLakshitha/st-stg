<?php

namespace App\Livewire\Marketplace\Admin\Product\Product;

use App\Models\Marketplace\ProductCategory;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductCreate extends Component
{
    use WithFileUploads;

    public $categories, $allCategories, $subCategories;
    public $name, $category_id, $description,
        $thumbnail, $display_price, $discount,
        $referer_commission, $course_point, $installment_1,
        $installment_2, $has_website, $course_price;
    public $is_discounted = false;

    public function mount()
    {
        $this->allCategories = ProductCategory::all();
        $this->categories = $this->allCategories->where('parent_id', NULL);
        $this->subCategories = $this->allCategories->whereNotNull('parent_id');
    }

    public function render()
    {
        return view('livewire.marketplace.admin.product.product.product-create');
    }
}
