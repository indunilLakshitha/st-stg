<?php

namespace App\Livewire\Course\Pay;

use App\Models\Course;
use App\Models\User;
use App\Models\UserBankDetail;
use App\Models\UserPuchasedCourse;
use App\Traits\ComissionTrait;
use App\Traits\CourseTrait;
use App\Traits\WalletTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DirectBank extends Component
{
    use WalletTrait, CourseTrait, ComissionTrait;

    public  $course, $amount, $purchasedPercent, $adminBankDetails;

    public function mount($courseId, $amount, $purchasedPercent)
    {
        $this->course = Course::where('id', $courseId)->first();
        $this->amount = $amount;
        $this->purchasedPercent = $purchasedPercent;
        $admins = User::where('is_admin', true)->pluck('id');
        $this->adminBankDetails = UserBankDetail::whereIn('user_id', $admins)->first();
    }

    public function render()
    {
        return view('livewire.course.pay.direct-bank');
    }

    public function purchase()
    {
        try {


            DB::beginTransaction();
            $user = Auth::user();

            $this->setApplliedCourse(
                userId: $user->id,
                courseId: $this->course?->id,
                type: UserPuchasedCourse::TYPE['DIRECT'],
                purchasedPercent: $this->purchasedPercent
            );

            /**
             * Add Direct Comission to Referral
             */
            $this->addDirectComission(
                userId: $user->referrer_id,
                amount: $this->course?->referer_commission
            );

            DB::commit();
            $this->dispatch('selects_alert', ['title' => 'Payment Success']);
            return redirect()->route('course.myCourses');
        } catch (Exception $e) {
            DB::rollback();
            $this->dispatch('failed_alert', ['title' => 'Payment Success']);
        }
    }
}
