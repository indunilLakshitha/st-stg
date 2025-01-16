<?php

namespace App\Livewire\MyTeam;

use App\Models\ComissionHistory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SalesChart extends Component
{
    public $leftAccounts, $rightAccounts, $totalLeftPoints, $totalRightPoints;
    public $selectedAccountId, $myTeam, $myMembers, $selectedUser;

    public function mount($userId = null)
    {
        $loggedUser =  Auth::user();
        if (!isset($userId)) {
            $this->selectedUser = $loggedUser;
        } else {
            $this->selectedUser = User::where('id', $userId)->first();
        }

        if (!isset($this->selectedUser)) {
            $this->selectedUser = $loggedUser;
        }
        $this->myMembers = User::where('approved_by_admin', true)
            // ->where('type', User::USER_TYPE['MAIN'])
            ->where('path', 'like', '%' . 'P' .  $loggedUser->unique_id  . '%')
            ->where('id', '!=', $loggedUser->id)
            ->select(
                'id',
                'reg_no',
                'first_name',
                'last_name',
                'path',
                'er_status',
                'name',
            )->get();


        $myCode = 'P' . $this->selectedUser->unique_id;

        $this->rightAccounts = User::where('approved_by_admin', true)
            // ->where('type', User::USER_TYPE['MAIN'])
            ->where('path', 'like', '%' .  $myCode . '%')
            ->where('id', '!=', $this->selectedUser->id)
            ->select(
                'id',
                'reg_no',
                'er_status',
                'first_name',
                'last_name',
                'name',
                'dummy_a2_id',
                'dummy_a1_id',
                'parent_id',
                'left_points',
                'right_points'
            )->where('path', 'like', '%' .  $myCode . 'SR'  . '%')
            ->orderBy('approved_at', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $this->leftAccounts = User::where('approved_by_admin', true)
            // ->where('type', User::USER_TYPE['MAIN'])
            ->where('path', 'like', '%' .  $myCode . '%')
            ->where('id', '!=', $this->selectedUser->id)
            ->select(
                'id',
                'reg_no',
                'er_status',
                'first_name',
                'last_name',
                'name',
                'dummy_a2_id',
                'dummy_a1_id',
                'parent_id',
                'left_points',
                'right_points'
            )->where('path', 'like', '%' .   $myCode . 'SL' . '%')
            ->orderBy('approved_at', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $this->totalLeftPoints = ComissionHistory::where('user_id', $this->selectedUser->id)->sum('left_points');
        $this->totalRightPoints = ComissionHistory::where('user_id', $this->selectedUser->id)->sum('right_points');
    }

    public function render()
    {
        return view('livewire.my-team.sales-chart');
    }

    public function filter()
    {
        $this->mount($this->selectedAccountId);
    }
    public function resetFilter()
    {
        $this->selectedAccountId = null;
        $this->mount($this->selectedAccountId);
    }
}
