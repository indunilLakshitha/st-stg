<?php

namespace App\Livewire\Admin\Customers;

use App\Helpers\CheckAdmin;
use App\Models\Course;
use App\Models\User;
use App\Traits\ComissionTrait;
use App\Traits\PathTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class RegistrationRequest extends Component
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
            ->select('id', 'reg_no', 'mobile_no', 'payment_status', 'referrer_id', 'name','created_at')
            ->get();
        $this->courses = Course::all();
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
                ->select('id', 'reg_no', 'mobile_no', 'payment_status', 'referrer_id', 'name','created_at')
                ->get();

            return;
        }
    }

    public function render()
    {
        return view('livewire.admin.customers.registration-request');
    }

    public function delete($id)
    {
        try {

            if (!Auth::user()->is_admin)
                return redirect()->route('dashboard');

            DB::beginTransaction();
            $customer = User::where('id', $id)
                ->where('approved_by_referrer', false)
                ->where('approved_by_admin', false)
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
            Log::error('RegistrationRequest : delete : ' . $e->getMessage());
            return $this->dispatch('failed_alert', ['title' => 'User Delete Failed']);
        }
    }
}
