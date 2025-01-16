<?php

namespace App\Livewire\Referral;

use App\Exceptions\PathNotSetException;
use App\Models\User;
use App\Services\UserService;
use App\Traits\MyTeamTrait;
use App\Traits\PathTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use PHPUnit\Event\Code\Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Approve extends Component
{
    use PathTrait, MyTeamTrait;

    public $user_id, $user, $first_name,
        $last_name, $nic, $mobile_no, $email, $address, $reg_no, $payment_type;
    public $path_list = [];
    public $logged_user;
    public $assigned_to, $assigned_user_side;

    public $A1_active = false, $A2_active = false;
    public $submit_active = true;
    public $keyword;
    public $filteredPaths = [];

    public  $allUsers;

    public function mount($id)
    {
        $this->user_id = $id;
        $user = User::where('id', $id)->first();
        $this->user = $user;
        $this->first_name = $user->first_name;
        $this->last_name = $user->last_name;
        $this->nic = $user->nic;
        $this->mobile_no = $user->mobile_no;
        $this->email = $user->email;
        $this->reg_no = $user->reg_no;
        $this->address = $user->address;
        if ($this->user->approved_by_referrer)
            $this->submit_active = false;

        $this->logged_user = Auth::user();
        $this->path_list = $this->getPathList(pathId: 'P' . $this->logged_user->unique_id);
        $this->payment_type =  User::USER_STATUS_LABLE['3'];
    }

    public function render()
    {
        return view('livewire.referral.approve');
    }

    public function getPathList(string $pathId)
    {
        /**
         * if main user is not ER activated -> main user shown in list -> only Agent 1 showing
         * if main user is  ER activated & not all dummies are ER activated -> main user shown in list ->  Agent 1 | Agent 2
         * if main user is  ER activated & not one dummy are ER activated -> main user shown in list ->  Agent 1 | Agent 2 && ER activated Dummy show -> Agent 1
         */
        $list = [];
        $availableUsers = collect([]);

        $allUsersOnMyPath = User::where('path', 'like', '%' . $pathId . '%')
            ->select(
                'id',
                'name',
                'path',
                'type',
                'my_left_a2_active',
                'my_right_a2_active',
                'dummy_a1_id',
                'dummy_a2_id',
                'approved_by_admin',
                'unique_id',
                'er_status'
            )
            ->get();

        $this->allUsers = $allUsersOnMyPath;

        $availableMainUsers = $this->getAvailableMainUsers(users: $allUsersOnMyPath);
        $availableDummyUsers = $this->getAvailableDummyUsers(users: $allUsersOnMyPath);

        $availableUsers = $availableUsers->merge($availableMainUsers);
        $availableUsers = $availableUsers->merge($availableDummyUsers);

        return $availableUsers;
    }

    public function approve()
    {

        // try {
        DB::beginTransaction();

        $user = $this->user;
        $user->payment_status = User::PAYMENT_STATUS[$this->payment_type];
        $user->approved_by = $this->logged_user->id;

        $avaulableUSerOnAssignedPath = $this->getAvailableUserOnSelectedPath();

        $user->assigned_user_id = $this->assigned_to;
        $user->approved_by_referrer = true;
        $user->approved_referrer_id = $this->logged_user->id;

        $parent = User::where('id', $this->assigned_to)->first();
        if (!isset($parent))
            return $this->dispatch('failed_alert', ['title' => 'Invalid Parent']);

        $user->assigned_user_side = $this->assigned_user_side;

        // $user->save();

        // $this->setPath(user: $user);

        DB::commit();
        // $this->submit_active = false;
        $this->dispatch('success_alert', ['title' => 'User Approved Success']);
        return redirect()->route('referrals.pending');
        // } catch (Exception $e) {

        //     DB::rollBack();
        //     Log::error($e->getMessage());

        //     return $this->dispatch('failed_alert', ['title' => 'User Approved Failed']);
        // }
    }
    public function approveOld()
    {

        try {
            DB::beginTransaction();

            $user = $this->user;
            $user->payment_status = User::PAYMENT_STATUS[$this->payment_type];
            $user->approved_by = $this->logged_user->id;


            $user->assigned_user_id = $this->assigned_to;
            $user->approved_by_referrer = true;
            $user->approved_referrer_id = $this->logged_user->id;

            $parent = User::where('id', $this->assigned_to)->first();
            if (!isset($parent))
                return $this->dispatch('failed_alert', ['title' => 'Invalid Parent']);

            $user->assigned_user_side = $this->assigned_user_side;

            $user->save();

            $this->setPath(user: $user);

            DB::commit();
            // $this->submit_active = false;
            $this->dispatch('success_alert', ['title' => 'User Approved Success']);
            return redirect()->route('referrals.pending');
        } catch (Exception $e) {

            DB::rollBack();
            Log::error($e->getMessage());

            return $this->dispatch('failed_alert', ['title' => 'User Approved Failed']);
        }
    }

    public function getAvailableUserOnSelectedPath()
    {
        $selectedUser = User::where('id', $this->assigned_to)->first();
        $selectedUserSide = $this->assigned_user_side;

        $pathId = 'P' . $selectedUser->unique_id;
        if ($selectedUserSide == 'A1') {
            $pathId = $pathId . 'SL';
        }

        if ($selectedUserSide == 'A2') {
            $pathId = $pathId . 'SR';
        }

        $availableUsers = User::where('path', 'like', '%' . $pathId . '%')
            ->select(
                'id',
                'name',
                'path',
                'type',
                'my_left_a2_active',
                'my_right_a2_active',
                'dummy_a1_id',
                'dummy_a2_id',
                'approved_by_admin',
                'unique_id',
                'er_status'
            )
            ->get();

        $parent = null;
        foreach ($availableUsers as $user) {
            if ($selectedUserSide == 'A1' && !isset($user->my_left_a1_id)) {
                $parent = $user;
            }

            if ($selectedUserSide == 'A2' && !isset($user->my_right_a1_id)) {
                $parent = $user;
            }
        }

        return $parent;
    }

    public function getPathBySearch()
    {
        $this->filteredPaths = [];
        $filtered = [];
        foreach ($this->allUsers as $user) {
            if (str_contains($user->unique_id, $this->keyword)) {
                array_push($filtered, $user);
            }
        }
        $this->filteredPaths = collect($filtered);
    }


    public function setPathValue($user)
    {
        $this->assigned_to = $user['id'];
        $this->keyword = $user['name'] . ' - ' . $user['unique_id'];
        $this->filteredPaths = [];
        $this->selectPath();
    }

    public function setPath(User $user)
    {
        $this->assigned_to = $user->assigned_user_id;

        $parent = User::where('id', $this->assigned_to)->first();
        if (!isset($parent))
            return $this->dispatch('failed_alert', ['title' => 'Invalid Parent']);

        $this->assigned_user_side =     $user->assigned_user_side;

        $user->save();

        if ($parent->er_status != User::USER_STATUS['ER'] && $parent->type == User::USER_TYPE['MAIN']) {

            /**
             * SET PARENT DETAILS IF PARENT IS NOT ER ENABLED AND MAIN
             */
            $this->setParentForErDisabled(user: $user, parent: $parent);
        } elseif ($parent->er_status != User::USER_STATUS['ER'] && $parent->type != User::USER_TYPE['MAIN']) {

            /**
             * SET PARENT DETAILS IF PARENT IS  ER DIABLED AND DUMMY
             */
            $this->setParentForErDisabledDummy(user: $user, parent: $parent);
        } elseif ($parent->er_status == User::USER_STATUS['ER'] && $parent->type != User::USER_TYPE['MAIN']) {

            /**
             * SET PARENT DETAILS IF PARENT IS  ER ENABLED AND DUMMY
             */
            $this->setParentForErEnabled(user: $user, parent: $parent);
        } else {

            throw new PathNotSetException("Path Not Set");
        }

        $user->save();
    }

    public function selectPath()
    {
        $this->A1_active = false;
        $this->A2_active = false;

        $selectedPathUser = User::where('id', $this->assigned_to)
            ->select(
                'id',
                'type',
                'my_left_a1_id',
                'my_right_a1_id',
                'dummy_a1_id',
                'er_Status',
                'dummy_a2_id'
            )
            ->first();

        $this->A1_active = true;
        if ($selectedPathUser->er_Status == User::USER_STATUS['ER']) {
            $this->A2_active = true;
        }
    }

    /**
     * THIS WAS REMOVED AS PER REQUESTED : 17/12/24
     */
    public function selectPathOld()
    {
        $selectedPathUser = User::where('id', $this->assigned_to)
            ->select(
                'id',
                'type',
                'my_left_a1_id',
                'my_right_a1_id',
                'dummy_a1_id',
                'er_Status',
                'dummy_a2_id'
            )
            ->first();
        if ($selectedPathUser->type == User::USER_TYPE['MAIN']) {
            if ($selectedPathUser->er_Status == User::USER_STATUS['ER']) {


                if (!isset($selectedPathUser->my_left_a1_id))
                    $this->A1_active = true;
                else
                    $this->A1_active = false;

                if (!isset($selectedPathUser->my_right_a1_id))
                    $this->A2_active = true;
                else
                    $this->A2_active = false;
            }
            if ($selectedPathUser->er_Status != User::USER_STATUS['ER']) {

                if (!isset($selectedPathUser->my_left_a1_id))
                    $this->A1_active = true;
                else
                    $this->A1_active = false;
            }
        }

        if ($selectedPathUser->type != User::USER_TYPE['MAIN']) {

            if (!isset($selectedPathUser->dummy_a1_id))
                $this->A1_active = true;
            else
                $this->A1_active = false;

            if (
                !isset($selectedPathUser->dummy_a2_id)
                && $selectedPathUser->er_status == User::USER_STATUS['ER']
            ) {

                $this->A2_active = true;
            } else {
                $this->A2_active = false;
            }
        }
    }

    /**
     * SET PARENT DETAILS IF PARENT IS NOT ER ENABLED
     * @param User $user
     * @param User $parent
     */
    private function setParentForErEnabled(User $user, User $parent)
    {
        $referral = User::where('id', $user->referrer_id)->first();
        $sideToPath = null;
        if ($this->assigned_user_side == User::USER_TYPE['LEFT'] && $parent->type == User::USER_TYPE['LEFT']) {
            if (!isset($parent->my_left_a1_id)) {
                $referral->my_left_a1_id = $user->id;
                $parent->dummy_a1_id = $user->id;
                $user->parent_id =  $parent->id;
                $sideToPath = 'SL';
            }
        }

        if ($this->assigned_user_side == User::USER_TYPE['RIGHT'] && $parent->type == User::USER_TYPE['LEFT']) {
            if (!isset($parent->my_left_a2_id)) {
                $referral->my_left_a2_id = $user->id;
                $parent->dummy_a2_id = $user->id;
                $user->parent_id =  $parent->id;
                $sideToPath = 'SL';
            }
        }

        if ($this->assigned_user_side == User::USER_TYPE['LEFT']  && $parent->type == User::USER_TYPE['RIGHT']) {
            if (!isset($parent->my_right_a1_id)) {
                $referral->my_right_a1_id = $user->id;
                $parent->dummy_a1_id = $user->id;
                $user->parent_id = $parent->id;
                $sideToPath = 'SR';
            }
        }

        if ($this->assigned_user_side == User::USER_TYPE['RIGHT']  && $parent->type == User::USER_TYPE['RIGHT']) {
            if (!isset($parent->my_right_a2_id)) {
                $referral->my_right_a2_id = $user->id;
                $parent->dummy_a2_id = $user->id;
                $user->parent_id = $parent->id;
                $sideToPath = 'SR';
            }
        }
        $actualParent = $parent;
        $user->path = $this->getMyPath(
            parentPath: $actualParent->path,
            myUniqueId: $user->unique_id,
            parentSide: $sideToPath
        );

        $user->save();
        $parent->save();
        $referral->save();
    }

    /**
     * SET PARENT DETAILS IF PARENT IS ER DISABLED
     * @param User $user
     * @param User $parent
     */
    private function setParentForErDisabled(User $user, User $parent)
    {
        $sideToPath = null;
        if ($this->assigned_user_side == User::USER_TYPE['LEFT']) {
            if (!isset($parent->my_left_a1_id)) {

                $parent->my_left_a1_id = $user->id;
                $actualParent = $this->getLeftSideUser(parent_id: $parent->id);
                $user->parent_id =  $actualParent->id;
                $sideToPath = 'SL';
            }
        }

        if ($this->assigned_user_side == User::USER_TYPE['RIGHT']) {
            if (!isset($parent->my_right_a1_id)) {

                $parent->my_right_a1_id = $user->id;
                $actualParent  = $this->getRightSideUser(parent_id: $parent->id);
                $user->parent_id = $actualParent->id;
                $sideToPath = 'SR';
            }
        }

        $user->path = $this->getMyPath(parentPath: $actualParent->path, myUniqueId: $user->unique_id, parentSide: $sideToPath);

        $actualParent->save();
        $user->save();
        $parent->save();
    }

    /**
     * SET PARENT DETAILS IF PARENT IS ER DISABLED
     * @param User $user
     * @param User $parent
     */
    private function setParentForErDisabledDummy(User $user, User $parent)
    {
        $mainParent = User::where('id', $parent->parent_id)->first();
        $sideToPath = null;

        if ($this->assigned_user_side == User::USER_TYPE['LEFT']) {
            if (!isset($parent->my_left_a1_id)) {
                $mainParent->my_left_a1_id = $user->id;
                $parent->dummy_a1_id = $user->id;
                $actualParent = $parent;
                $user->parent_id =  $actualParent->id;
                $sideToPath = 'SL';
            }
        }

        if ($this->assigned_user_side == User::USER_TYPE['RIGHT']) {
            if (!isset($parent->my_right_a1_id)) {
                $mainParent->my_right_a1_id = $user->id;
                $parent->dummy_a2_id = $user->id;
                $actualParent  = $parent;
                $user->parent_id = $actualParent->id;
                $sideToPath = 'SR';
            }
        }

        $user->path = $this->getMyPath(parentPath: $actualParent->path, myUniqueId: $user->unique_id, parentSide: $sideToPath);

        $mainParent->save();
        $user->save();
        $parent->save();
    }
}
