<?php

namespace App\Livewire\Admin\Courses\Course;

use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Courses extends Component
{
    public $courses, $categories, $selected_category;

    public function mount()
    {
        $this->courses = Course::with('category')->get();
        $this->categories = CourseCategory::all();
    }

    public function render()
    {
        return view('livewire.admin.courses.course.courses');
    }

    public function filter()
    {
        $this->courses = [];
        $this->courses = Course::with('category')
            ->where('category_id', $this->selected_category)
            ->get();

        if ($this->selected_category == 0) {
            $this->courses = Course::with('category')
                ->get();
        }
    }


    public function delete($id)
    {
        $courses = Course::where('id', $id)->first();

        if (Auth::user()->is_admin) {
            $courses->delete();

            // session()->flash('message', 'Course successfully Deleted.');
            $this->dispatch('success_alert', ['title' => 'Course successfully Deleted']);

            $this->mount();
            return;
        }

        return abort(404);
    }
}
