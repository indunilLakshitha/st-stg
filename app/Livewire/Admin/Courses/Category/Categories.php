<?php

namespace App\Livewire\Admin\Courses\Category;

use App\Models\CourseCategory;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Categories extends Component
{
    public $categories;
    public $category;

    public function mount()
    {
        $this->categories = CourseCategory::all();
    }

    public function render()
    {
        return view('livewire.admin.courses.category.categories');
    }

    public function delete($id)
    {
        $category = CourseCategory::where('id', $id)->first();

        if (!isset($category) || !Auth::user()->is_admin)
            abort(404);

        $category->delete();

        // session()->flash('message', 'Category successfully Deleted.');
        $this->dispatch('success_alert', ['title' => 'Categorysuccessfully Deleted.']);

        $this->mount();
    }
}
