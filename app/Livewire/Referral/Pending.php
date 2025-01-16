<?php

namespace App\Livewire\Referral;

use App\Models\Course;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Pending extends Component
{
    public $customers;
    public $selected_course, $courses;

    public function mount()
    {
        $this->customers = User::with('purchase.course')->where('approved_by_admin', false)
            ->where('parent_id', NULL)
            ->where('referrer_id', '!=', NULL)
            ->where('assigned_user_id', NULL)
            ->where('approved_referrer_id', NULL)
            ->where('approved_by_referrer', false)
            ->where('referrer_id', Auth::user()->reg_no)
            ->select('id', 'reg_no', 'mobile_no', 'payment_status', 'referrer_id', 'name')
            ->get();
        $this->courses = Course::all();
    }

    public function render()
    {
        return view('livewire.referral.pending');
    }

    public function filter()
    {

        if ($this->selected_course == 0) {
            $this->customers = User::with('purchase.course')
                ->where('approved_by_admin', false)
                ->where('parent_id', NULL)
                ->where('referrer_id', '!=', NULL)
                ->where('assigned_user_id', NULL)
                ->where('approved_referrer_id', NULL)
                ->where('approved_by_referrer', false)
                ->where('referrer_id', Auth::user()->reg_no)

                ->select('id', 'reg_no', 'mobile_no', 'payment_status', 'referrer_id', 'name')
                ->get();

            return;
        }

        // $this->customers = User::with('purchase.course', function ($q) {
        //     $q->where('course_id', $this->selected_course);
        // })->where('approved_by_admin', false)
        //     ->where('parent_id', NULL)
        //     ->where('referrer_id', '!=', NULL)
        //     ->where('assigned_user_id', NULL)
        //     ->where('approved_referrer_id', NULL)
        //     ->where('approved_by_referrer', false)
        //     ->where('referrer_id', Auth::user()->reg_no)

        //     ->select('id', 'reg_no', 'mobile_no', 'payment_status', 'referrer_id', 'name')
        //     ->get();
    }

    public function delete($id)
    {
        try {

            DB::beginTransaction();
            $customer = User::where('id', $id)
                ->where('approved_by_referrer', false)
                ->where('approved_by_admin', false)
                ->where('referrer_id', Auth::user()->id)
                ->first();

            if (!isset($customer))
                return $this->dispatch('failed_alert', ['title' => 'User Delete Failed']);

            User::where('parent_id', $customer->id)->forceDelete();
            $customer->forceDelete();

            DB::commit();
            $this->mount();
            return $this->dispatch('success_alert', ['title' => 'User successfully Deleted']);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('delete : pending : ' . $e->getMessage());
            return $this->dispatch('failed_alert', ['title' => 'User Delete Failed']);
        }
    }
}
