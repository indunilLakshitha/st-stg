<?php

namespace App\Livewire\Course;

use App\Models\Course;
use App\Models\User;
use App\Models\UserPuchasedCourse;
use App\Models\Wallet;
use App\Traits\ComissionTrait;
use App\Traits\CourseTrait;
use App\Usecases\Point\PointsToReferralUsecase;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CourseDetail extends Component
{
    use ComissionTrait, CourseTrait;

    public $courseId, $course;
    public  $selectedCourse, $walletBalance = 0;

    public function mount($courseId)
    {
        $this->course = Course::where('id', $courseId)->first();
        $this->walletBalance = Wallet::where('user_id', Auth::user()->id)->first()->balance;
    }
    public function render()
    {
        return view('livewire.course.course-detail');
    }

    public function payAlert()
    {
        return $this->dispatch('selects_alert', ['title' => 'How does this purchase ?']);
    }
    public function payFull($courseId)
    {
        $this->pay(courseId: $courseId, type: 2);
    }
    public function payHalf($courseId)
    {
        $this->pay(courseId: $courseId, type: 1);
    }

    public function pay($courseId, $type)
    {
        $user = Auth::user();

        $appliedCourse = Course::where('id', $courseId)
            ->first();

        $this->setApplliedCourse(
            userId: $user->id,
            courseId: $courseId,
            type: UserPuchasedCourse::TYPE['DIRECT'],
            purchasedPercent: $type
        );

        if (!isset($appliedCourse))
            abort(404);

        if (!isset($user->referrer_id))
            $user = User::where('id', $user->parent_id)->first();



        /**
         * Add Direct Comission to Referral
         */
        $this->addDirectComission(
            userId: $user->referrer_id,
            amount: $appliedCourse->referer_commission
        );



        /**
         * Add points to Referrals
         */
        (new PointsToReferralUsecase())->handle(user: $user, point: $appliedCourse->course_point);
        return $this->dispatch('success_alert', ['title' => 'Paid Successfully']);
    }
}
