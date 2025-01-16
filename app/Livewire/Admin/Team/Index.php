<?php

namespace App\Livewire\Admin\Team;

use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public $customers, $users;
    public $selected_user;
    public $user, $myCode, $type = 1;

    public function mount()
    {
        $this->users = User::where('is_admin', false)
            ->where('approved_by_admin', true)->get();

        if (Auth::user()->is_admin)
            $this->customers = User::with('purchase.course')
                ->where('approved_by_admin', true)
                ->select(
                    'id',
                    'reg_no',
                    'mobile_no',
                    'payment_status',
                    'referrer_id',
                    'name',
                    'er_status',
                    'assigned_user_side'
                )
                ->where('type', User::MAIN)
                ->orderBy('approved_at', 'asc')
                ->orderBy('id', 'asc')
                ->get();
    }

    public function render()
    {
        return view('livewire.admin.team.index');
    }

    public function filter()
    {
        $this->user = User::where('id', $this->selected_user)->first();

        if (isset($this->user->unique_id))
            $this->myCode = 'P' . $this->user->unique_id;

        $customers = User::with('purchase.course')
            ->where('approved_by_admin', true)
            // ->where('path', 'like', '%' .  $this->myCode . '%')
            ->select(
                'id',
                'reg_no',
                'mobile_no',
                'payment_status',
                'referrer_id',
                'name',
                'er_status',
                'assigned_user_side'
            );

        if (isset($this->user->unique_id))
            $customers = $customers->where('path', 'like', '%' .  $this->myCode . '%');

        $customers = $customers->when($this->type, function ($q) {
            if ($this->type == 1)
                return $q->where('type', User::MAIN);

            if ($this->type == 2)
                return $q->where('type', '!=', User::MAIN);
        });

        $this->customers = $customers
            ->orderBy('approved_at', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    }
}
