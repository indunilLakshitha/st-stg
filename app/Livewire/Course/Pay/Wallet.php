<?php

namespace App\Livewire\Course\Pay;

use App\Models\Course;
use App\Models\UserPuchasedCourse;
use App\Models\Wallet as ModelsWallet;
use App\Traits\ComissionTrait;
use App\Traits\CourseTrait;
use App\Traits\WalletTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Wallet extends Component
{
    use WalletTrait, CourseTrait, ComissionTrait;

    public $walletBalance, $course, $amount, $purchasedPercent;

    public function mount($courseId, $amount, $purchasedPercent)
    {
        $this->walletBalance = ModelsWallet::where('user_id', Auth::user()->id)->first()->balance;
        $this->course = Course::where('id', $courseId)->first();
        $this->amount = $amount;
        $this->purchasedPercent = $purchasedPercent;
    }

    public function render()
    {
        return view('livewire.course.pay.wallet');
    }

    public function purchase()
    {
        try {


            DB::beginTransaction();
            $user = Auth::user();
            $wallet = ModelsWallet::where('user_id', $user->id)->first();
            if ($wallet->balance < $this->amount) {
                return  $this->dispatch('failed_alert', ['title' => 'No Enough Wallet Balance']);
            }
            $wallet->balance -= $this->amount;
            $wallet->save();

            $this->setWalletHistory(
                userId: Auth::user()->id,
                wallet: $wallet,
                amount: $this->amount,
                type: 2,
                comissionType: ModelsWallet::COMISSION_TYPE['PURCHASE']
            );

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
