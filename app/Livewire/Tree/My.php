<?php

namespace App\Livewire\Tree;

use App\Models\MasterData;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class My extends Component
{
    public $me, $myTeam;
    public $selectedTeam;

    public function mount()
    {
        $loggedUser = Auth::user();
        if ($loggedUser->is_admin) {
            $loggedUser = User::where('id', MasterData::first()->default_parent_id)->first();
        }

        // $user = User::where('id', $loggedUser->id)
        //     ->select('id', 'reg_no', 'status', 'name', 'dummy_a2_id', 'dummy_a1_id')
        //     ->first();
        if ($loggedUser->type != User::USER_TYPE['MAIN'])
            $loggedUser = User::where('id', $loggedUser->parent_id)->first();

        $user = User::with('childA2', 'childA1')
            ->where('id', $loggedUser->id)
            ->select(
                'id',
                'reg_no',
                'er_status',
                'name',
                'first_name',
                'last_name',
                'dummy_a2_id',
                'assigned_user_side',
                'dummy_a1_id',
                'left_points',
                'right_points'
            )
            ->first();

        $myCode = 'P' . $loggedUser->unique_id;
        $this->myTeam = User::with('childA2', 'childA1')
            ->where('approved_by_admin', true)
            ->where('type', User::USER_TYPE['MAIN'])
            ->where('path', 'like', '%' .  $myCode . '%')
            ->where('id', '!=', $user->id)
            ->select(
                'id',
                'reg_no',
                'er_status',
                'name',
                'first_name',
                'last_name',
                'dummy_a2_id',
                'assigned_user_side',
                'dummy_a1_id',
                'parent_id',
                'left_points',
                'right_points'
            )
            ->orderByraw('CHAR_LENGTH(path) ASC')
            // ->orderBy('parent_id', 'asc')
            ->get();



        $this->me = $user;

        // $tree = [$user];
    }

    public function render()
    {
        return view('livewire.tree.my');
    }

    // calculate depth and insert
    public function getStatus($status)
    {

        if (isset($status))
            return User::USER_STATUS_LABLE[$status];
    }

    public function showHide($id)
    {
        $this->mount();
        $selectedUser = User::with('childA2', 'childA1')
            ->where('id', $id)
            ->select(
                'id',
                'reg_no',
                'er_status',
                'name',
                'first_name',
                'last_name',
                'dummy_a2_id',
                'assigned_user_side',
                'dummy_a1_id',
                'left_points',
                'right_points'
            )
            ->first();

        $selectedCode = 'P' . $selectedUser->unique_id;
        $this->selectedTeam = User::with('childA2', 'childA1')
            ->where('approved_by_admin', true)
            ->where('type', User::USER_TYPE['MAIN'])
            ->where('path', 'like', '%' .  $selectedCode . '%')
            ->where('id', '!=', $selectedUser->id)
            ->select(
                'id',
                'reg_no',
                'er_status',
                'name',
                'first_name',
                'last_name',
                'dummy_a2_id',
                'assigned_user_side',
                'dummy_a1_id',
                'parent_id',
                'left_points',
                'right_points'
            )
            ->orderByraw('CHAR_LENGTH(path) ASC')
            // ->orderBy('parent_id', 'asc')
            ->get();

        $this->dispatch('team_selected', ['title' => 'ccc']);
    }
}
