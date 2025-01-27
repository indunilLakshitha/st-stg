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
    public $selected =
    [
        'name' => '',
        'contact' => '',
        'city' => '',
        'district' => '',
        'agent_1' => 0,
        'agent_2' => 0,
        'id' => '',
        'a1' => 0,
        'a2' => 0,
    ];

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

    public function setSelectedUser($id)
    {
        $user = User::with('city', 'district', 'childA1', 'childA2')
            ->where('id', $id)
            ->select(
                'id',
                'dashboard_city_id',
                'dashboard_district_id',
                'left_points',
                'right_points',
                'dummy_a1_id',
                'dummy_a2_id',
                'id',
                'mobile_no',
                'last_name',
                'first_name',
            )
            ->first();
        return  $this->setModalData(user: $user);
    }

    public function setModalData(User $user)
    {

        $this->selected['name'] = $user->first_name . ' ' . $user->last_name;
        $this->selected['city'] = $user->city?->name_en;
        $this->selected['contact'] = $user->mobile_no;
        $this->selected['id'] = $user->id;
        $this->selected['agent_1'] = $user->childA1?->left_points + $user->childA1?->right_points;
        $this->selected['agent_2'] = $user->childA2?->left_points + $user->childA2?->right_points;
        $this->selected['a1'] = $user->left_points;
        $this->selected['a2'] = $user->right_points;
        $this->selected['district'] = $user->district?->name_en;
        return $this->dispatch('show_modal', ['title' => '']);
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
