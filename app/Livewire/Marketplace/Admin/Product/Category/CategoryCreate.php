<?php

namespace App\Livewire\Marketplace\Admin\Product\Category;

use App\Models\Marketplace\ProductCategory;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class CategoryCreate extends Component
{
    public $cat_name;

    public function render()
    {
        return view('livewire.marketplace.admin.product.category.category-create');
    }

    public function saveCategory()
    {
        $this->validate();
        try {

            DB::beginTransaction();
            $cat = new ProductCategory();
            $cat->cat_name = $this->cat_name;

            $cat->save();

            $this->clear();
            $this->dispatch('success_alert', ['title' => 'Category successfully Saved']);
            DB::commit();
            return;
        } catch (Exception $e) {

            DB::rollBack();
            Log::alert('MARKETPLACE :: saveCategory :: ' . $e->getMessage());
        }
    }

    protected function rules()
    {
        return  [
            'cat_name' => 'required',

        ];
    }

    public function clear()
    {
        $this->cat_name = "";
    }
}
