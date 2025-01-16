<?php

namespace App\Livewire\Admin\Courses\Category;

use App\Models\CourseCategory;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class UpdateCategory extends Component
{
    public $id;
    public $category;
    public $cat_name;

    public function mount($id)
    {
        $this->id = $id;

        $category = CourseCategory::where('id', $id)->first();

        if (!isset($category) || !Auth::user()->is_admin)
            abort(404);

        $this->cat_name = $category->cat_name;
        $this->category = $category;
    }

    public function render()
    {
        return view('livewire.admin.courses.category.update-category');
    }

    public function updateCategory()
    {
        $this->validate();

        try {

            DB::beginTransaction();
            $this->category->cat_name = $this->cat_name;

            $this->category->save();

            DB::commit();

            // return session()->flash('message', 'Category successfully Updated.');
            return $this->dispatch('success_alert', ['title' => 'Category successfully Updated']);
        } catch (Exception $e) {

            DB::rollBack();
            Log::alert('updateCategory :: ' . $e->getMessage());
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
