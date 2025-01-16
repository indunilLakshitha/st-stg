<?php

namespace App\Livewire\Admin\Sales;

use App\Models\Course;
use Livewire\Component;

class Index extends Component
{
    public $courses, $categories, $selected_payment;

    public function mount()
    {
        $this->courses = Course::join('user_puchased_courses', 'user_puchased_courses.course_id', 'courses.id')
            ->join('users', 'users.id', 'user_puchased_courses.user_id')
            ->where('users.approved_by_admin', true)
            ->select(
                'user_puchased_courses.*',
                'courses.name as course_name',
                'courses.course_price',
                'users.name as user_name',
                'users.approved_by_admin'
            )
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.sales.index');
    }

    public function filter()
    {
        $this->courses = Course::join('user_puchased_courses', 'user_puchased_courses.course_id', 'courses.id')
            ->join('users', 'users.id', 'user_puchased_courses.user_id')
            ->select(
                'user_puchased_courses.*',
                'courses.name as course_name',
                'courses.course_price',
                'users.name as user_name'
            )
            ->where('user_puchased_courses.purchased_percent', $this->selected_payment)
            ->get();
    }
}
