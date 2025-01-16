<?php

namespace App\Livewire\Home;

use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Illuminate\Support\Facades\Session;

class Courses extends Component
{
    public $courses, $referral_id, $course_id;

    public function mount($referral_id)
    {

        $session_ref = session('referral_id');

        if (strlen($referral_id) > 0) {
            Log::debug('sss');
            session(['referral_id' =>  $referral_id]);
            $this->referral_id = $referral_id;
        } else if (strlen($session_ref) > 0) {
            $this->referral_id = $session_ref;
        }

        $this->courses = Course::with('category')->where('has_website', 1)->get();
        // dd($session_ref);
        Log::debug('ref ' . $this->referral_id);
    }

    public function render()
    {
        return view('livewire.home.courses');
    }

    public function selectCourse($course_id)
    {
        if (strlen($this->referral_id) > 1) {
            if (isset($this->referral_id)) {
                $referrel = User::find($this->referral_id);
                if (!isset($referrel)) {
                    return $this->dispatch('failed_alert', ['title' => 'Invalid Referrel User']);
                }
            }
        } else {

            return redirect()->route('regWithcourse', [$course_id]);
        }

        return redirect()->route('home.registerWithRef', [$this->referral_id, $course_id]);
    }
}
