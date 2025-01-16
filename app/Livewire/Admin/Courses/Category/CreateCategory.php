<?php

namespace App\Livewire\Admin\Courses\Category;

use App\Models\CourseCategory;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class CreateCategory extends Component
{
    public $cat_name;

    public function render()
    {
        return view('livewire.admin.courses.category.create-category');
    }

    public function saveCategory()
    {
        $this->validate();

        try {

            DB::beginTransaction();
            $cat = new CourseCategory();
            $cat->cat_name = $this->cat_name;

            $cat->save();

            DB::commit();
            $this->clear();
            $this->dispatch('success_alert', ['title' => 'Category successfully Saved']);
            return;
        } catch (Exception $e) {

            DB::rollBack();
            Log::alert('saveCategory :: ' . $e->getMessage());
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
