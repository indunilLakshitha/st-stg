<?php

namespace App\Livewire\MyTeam;

use App\Helpers\CheckAdmin;
use App\Models\Course;
use App\Models\User;
use App\Traits\ComissionTrait;
use App\Traits\PathTrait;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PendingActivations extends Component
{
    use PathTrait, ComissionTrait;

    public $customers;
    public $payment_type, $assigned_to, $assigned_user_side, $selected_user_id;

    public $courses;
    public $selected_course, $user_selected_courses = [];
    public $course_list = [];
    public $ignore = true;

    public function mount()
    {

        $this->customers = User::with('referrer')
            ->leftjoin('user_puchased_courses', 'user_puchased_courses.user_id', 'users.id')
            ->leftjoin('courses', 'courses.id', 'user_puchased_courses.course_id')
            ->where('users.approved_by_admin', false)
            ->where('users.is_admin', false)
            ->whereNot('users.approved_referrer_id', NULL)
            ->where('users.approved_by_referrer', true)
            ->where('users.referrer_id', Auth::user()->id)
            ->where('users.type', User::USER_TYPE['MAIN'])
            ->select(
                'users.id',
                'users.reg_no',
                'users.mobile_no',
                'users.er_status',
                'users.payment_status',
                'users.referrer_id',
                'users.name',
                'users.assigned_user_id_on_approval',
                'users.assigned_user_side_on_approval',
                'users.status',
                'user_puchased_courses.user_id',
                'user_puchased_courses.course_id',
                'courses.name as course_name',
            )
            ->get();

        foreach ($this->customers as $user) {
            array_push($this->user_selected_courses, [$user->id => $user->course_id]);
        }
        // dd( $this->customers);
        $this->courses = Course::all();
    }
    public function render()
    {
        return view('livewire.my-team.pending-activations');
    }

    public function delete($id)
    {

        $customer = User::where('id', $id)
            ->where('approved_by_referrer', true)
            ->where('approved_by_admin', false)
            ->first();

        if (!isset($customer))
            return $this->dispatch('failed_alert', ['title' => 'User Delete Failed']);

        User::where('parent_id', $customer->id)->forceDelete();
        $customer->forceDelete();
        $this->mount();
        return $this->dispatch('success_alert', ['title' => 'User successfully Deleted']);
    }

    public function filter()
    {
        $this->ignore = false;
        if ($this->selected_course == 0) {

            $this->customers = User::with('referrer')
                ->leftjoin('user_puchased_courses', 'user_puchased_courses.user_id', 'users.id')
                ->leftjoin('courses', 'courses.id', 'user_puchased_courses.course_id')
                ->where('users.approved_by_admin', false)
                ->where('users.is_admin', false)
                ->whereNot('users.approved_referrer_id', NULL)
                ->where('users.approved_by_referrer', true)
                ->where('users.referrer_id', Auth::user()->id)
                ->where('users.type', User::USER_TYPE['MAIN'])
                ->select(
                    'users.id',
                    'users.reg_no',
                    'users.mobile_no',
                    'users.er_status',
                    'users.payment_status',
                    'users.referrer_id',
                    'users.name',
                    'users.status',
                    'user_puchased_courses.user_id',
                    'user_puchased_courses.course_id',
                    'courses.name as course_name',
                )
                ->get();
        } else {



            $this->customers = User::with('referrer')
                ->leftjoin('user_puchased_courses', 'user_puchased_courses.user_id', 'users.id')
                ->leftjoin('courses', 'courses.id', 'user_puchased_courses.course_id')
                ->where('user_puchased_courses.course_id', $this->selected_course)
                ->where('users.approved_by_admin', false)
                ->where('users.is_admin', false)
                ->whereNot('users.approved_referrer_id', NULL)
                ->where('users.referrer_id', Auth::user()->id)
                ->where('users.approved_by_referrer', true)
                ->where('users.type', User::USER_TYPE['MAIN'])
                ->select(
                    'users.id',
                    'users.reg_no',
                    'users.mobile_no',
                    'users.er_status',
                    'users.payment_status',
                    'users.referrer_id',
                    'users.name',
                    'users.status',
                    'user_puchased_courses.user_id',
                    'user_puchased_courses.course_id',
                    'courses.name as course_name',
                )
                ->get();
        }
    }


    public function changeCourseOfUser($userId) {}
}
