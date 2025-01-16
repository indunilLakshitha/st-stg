<?php

namespace App\Livewire\MyTeam;

use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DirectTeam extends Component
{
    public $customers, $courses;
    public $selected_course;
    public $user, $myCode, $type = 1;

    public function mount()
    {
        $this->user = Auth::user();
        $this->myCode = 'P' . $this->user->unique_id;

        $this->customers = User::with('purchase.course')
            ->where('approved_by_admin', true)
            ->where('path', 'like', '%' .  $this->myCode . '%')
            ->where('type',  User::MAIN)
            ->select(
                'id',
                'reg_no',
                'type',
                'mobile_no',
                'payment_status',
                'referrer_id',
                'name',
                'er_status',
                'assigned_user_id',
                'assigned_user_side'
            )
            ->orderBy('approved_at', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $this->courses = Course::all();
    }

    public function render()
    {
        return view('livewire.my-team.direct-team');
    }

    public function filter()
    {
        $customers = User::query()->with('purchase.course')
            ->where('approved_by_admin', true)
            ->where('path', 'like', '%' .  $this->myCode . '%')
            ->select(
                'id',
                'reg_no',
                'mobile_no',
                'type',
                'payment_status',
                'referrer_id',
                'name',
                'er_status',
                'assigned_user_side'
            )
            ->orderBy('approved_at', 'asc')
            ->orderBy('id', 'asc');


        $customers = $customers->when($this->type, function ($q) {
            if ($this->type == 1)
                return $q->where('type', User::MAIN);

            if ($this->type == 2)
                return $q->where('type', '!=', User::MAIN);
        });

        $customers = $customers->when($this->selected_course, function ($q) {
            return $q->whereRelation('purchase.course', 'id', $this->selected_course);
        });

        $this->customers = [];
        $this->customers =  $customers->get();
    }

    public function resetFilter()
    {
        $this->mount();
    }
}
