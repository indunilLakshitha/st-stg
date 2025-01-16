<?php

namespace App\Livewire\Course;

use App\Models\Course;
use App\Models\User;
use App\Models\UserPuchasedCourse;
use App\Traits\ComissionTrait;
use App\Traits\CourseTrait;
use App\Usecases\Point\PointsToReferralUsecase;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MyCourses extends Component
{
    use CourseTrait, ComissionTrait;

    public $courses;

    public function mount()
    {
        $this->courses = UserPuchasedCourse::with('course')
            ->where('user_id', Auth::user()->id)->orderBy('id','desc')->get();
    }

    public function render()
    {
        return view('livewire.course.my-courses');
    }

    public function payAlert()
    {
        return $this->dispatch('selects_alert', ['title' => 'How does this purchase ?']);
    }
    public function payFull($courseId)
    {
        $this->pay(purchasedId: $courseId, type: 2);
    }
    public function payHalf($courseId)
    {
        $this->pay(purchasedId: $courseId, type: 1);
    }

    public function pay($purchasedId, $type)
    {
        $user = Auth::user();

        $userPurchased  = UserPuchasedCourse::where('id', $purchasedId)->first();

        $appliedCourse = Course::where('id', $userPurchased->course_id)
        ->first();

        $this->updateApplliedCourse(
            userPurchased: $userPurchased,
            purchasedPercent: $type
        );

        if (!isset($appliedCourse))
            abort(404);

        if (!isset($user->referrer_id))
            $user = User::where('id', $user->parent_id)->first();



        /**
         * Add Direct Comission to Referral
         */
        // $this->addDirectComission(
        //     userId: $user->referrer_id,
        //     amount: $appliedCourse->referer_commission
        // );



        /**
         * Add points to Referrals
         */
        // (new PointsToReferralUsecase())->handle(user: $user, point: $appliedCourse->course_point);
        $this->mount();
        return $this->dispatch('success_alert', ['title' => 'Paid Successfully']);
    }
}
