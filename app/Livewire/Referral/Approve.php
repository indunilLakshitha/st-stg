<?php

namespace App\Livewire\Referral;

use App\Exceptions\PathNotSetException;
use App\Models\ApproveHistory;
use App\Models\User;
use App\Services\UserService;
use App\Traits\MyTeamTrait;
use App\Traits\PathTrait;
use Carbon\Carbon;
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
        $last_name, $nic, $mobile_no, $email, $address, $reg_no, $payment_type, $course;
    public $path_list = [];
    public $logged_user;
    public $assigned_to, $assigned_user_side;

    public $A1_active = false, $A2_active = false;
    public $submit_active = true;
    public $keyword;
    public $filteredPaths = [];

    public  $allUsers, $availableUsers;

    public function mount($id)
    {
        $this->user_id = $id;
        $user = User::with('purchase.course')->where('id', $id)->first();
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

        $this->course = $user->purchase?->course?->name;
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

        $availableMainUsers = $availableMainUsers->merge($availableDummyUsers);
        $this->availableUsers = $availableMainUsers;

        return $this->availableUsers->sortByDesc('id');
    }

    public function approve()
    {

        try {
            DB::beginTransaction();

            $user = $this->user;
            $user->payment_status = User::PAYMENT_STATUS[$this->payment_type];
            $user->approved_by = $this->logged_user->id;
            $user->assigned_user_id_on_approval = $this->assigned_to;
            $user->assigned_user_side_on_approval = $this->assigned_user_side;
            $user->approved_by_referrer = true;
            $user->approved_referrer_id = $this->logged_user->id;
            $user->referrel_approved_at = Carbon::now();
            $user->save();
            User::where('parent_id', $user->id)->update(['referrel_approved_at' => $user->referrel_approved_at]);

            DB::commit();
            $this->submit_active = false;

            ApproveHistory::create([
                'user_id' => $user->id,
                'approved_referrer_id' => $user->approved_by,
                'assigned_id_by_referrer' => $this->assigned_to,
                'assigned_side_by_referrer' => $this->assigned_user_side,
                'referrer_approved_at' => $user->referrel_approved_at
            ]);

            $this->dispatch('success_alert', ['title' => 'User Approved Success']);
            return redirect()->route('referrals.pending');
        } catch (Exception $e) {

            DB::rollBack();
            Log::error($e->getMessage());

            return $this->dispatch('failed_alert', ['title' => 'User Approved Failed']);
        }
    }



    public function getAvaiableRightParent(User $selectedUser)
    {
        $pathId = 'P' . $selectedUser->unique_id;
        $pathId = $pathId;
        $ignoreSide = 'SR';
        $pathId = $pathId . 'SR';

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
                'my_left_a1_id',
                'my_right_a1_id',
                'approved_by_admin',
                'unique_id',
                'parent_id',
                'er_status'
            )
            ->get();

        $parent = null;
        if (sizeof($availableUsers) > 0) {
            foreach ($availableUsers as $user) {
                $pathAfterPathId = trim(substr($user->path, strpos($user->path, $pathId) + strlen($pathId)));
                if ($user->type == User::USER_TYPE['LEFT']) {
                    continue;
                }
                if ($user->type == User::USER_TYPE['MAIN']) {
                    if (
                        !isset($user->my_left_a1_id)
                        && !str_contains($pathAfterPathId, $ignoreSide)
                        && !str_contains($pathAfterPathId, 'R')
                    ) {
                        $parent = $user;
                    }
                }
                if ($user->type == User::USER_TYPE['LEFT']) {
                    if (
                        !isset($user->dummy_a1_id)
                        && !str_contains($pathAfterPathId, $ignoreSide)
                        && !str_contains($pathAfterPathId, 'R')
                    ) {
                        $parent = $user;
                    }
                }
            }
        } else {
            if (!isset($selectedUser->my_left_a1_id)) {
                $parent = $selectedUser;
            }
        }

        // dd($parent);
        return $parent;
    }

    public function getPathBySearch()
    {
        $this->filteredPaths = [];
        $filtered = [];
        foreach ($this->availableUsers as $user) {
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



    public function selectPath()
    {
        $this->A1_active = false;
        $this->A2_active = false;

        if (!isset($this->assigned_to) || !strlen($this->assigned_to) > 0) {
            return;
        }

        $selectedPathUser = User::where('id', $this->assigned_to)
            ->select(
                'id',
                'type',
                'my_left_a1_id',
                'my_right_a1_id',
                'dummy_a1_id',
                'er_status',
                'dummy_a2_id'
            )
            ->first();

        $this->A1_active = true;
        if ($selectedPathUser->er_status == User::USER_STATUS['ER']) {
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
                'er_status',
                'dummy_a2_id'
            )
            ->first();
        if ($selectedPathUser->type == User::USER_TYPE['MAIN']) {
            if ($selectedPathUser->er_status == User::USER_STATUS['ER']) {


                if (!isset($selectedPathUser->my_left_a1_id))
                    $this->A1_active = true;
                else
                    $this->A1_active = false;

                if (!isset($selectedPathUser->my_right_a1_id))
                    $this->A2_active = true;
                else
                    $this->A2_active = false;
            }
            if ($selectedPathUser->er_status != User::USER_STATUS['ER']) {

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
