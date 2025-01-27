<?php

namespace App\Livewire\Admin\Customers;

use App\Helpers\CheckAdmin;
use App\Jobs\SendMailJob;
use App\Jobs\SendSmsJob;
use App\Models\ApproveHistory;
use App\Models\Course;
use App\Models\MailDetail;
use App\Models\User;
use App\Models\UserPuchasedCourse;
use App\Services\UserService;
use App\Traits\ComissionTrait;
use App\Traits\PathTrait;
use App\Traits\SMSTrait;
use App\Usecases\Point\PointsToReferralUsecase;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Requests extends Component
{
    use PathTrait, ComissionTrait, SMSTrait;

    public $customers;
    public $payment_type, $assigned_to, $assigned_user_side, $selected_user_id;

    public $courses;
    public $selected_course, $user_selected_courses = [];
    public $course_list = [];
    public $ignore = true;

    public function mount()
    {
        if (!CheckAdmin::check())
            abort(403);

        $this->customers = User::with('referrer')
            ->leftjoin('user_puchased_courses', 'user_puchased_courses.user_id', 'users.id')
            ->leftjoin('courses', 'courses.id', 'user_puchased_courses.course_id')
            ->where('users.approved_by_admin', false)
            ->where('users.is_admin', false)
            ->whereNot('users.approved_referrer_id', NULL)
            ->where('users.approved_by_referrer', true)
            ->where('users.type', User::USER_TYPE['MAIN'])
            ->select(
                'users.id',
                'users.reg_no',
                'users.mobile_no',
                'users.er_status',
                'users.payment_status',
                'users.referrer_id',
                'users.name',
                'users.assigned_user_id_on_approval',
                'users.assigned_user_side_on_approval',
                'users.status',
                'user_puchased_courses.user_id',
                'user_puchased_courses.course_id',
                'courses.name as course_name',
            )
            ->get();

        foreach ($this->customers as $user) {
            array_push($this->user_selected_courses, [$user->id => $user->course_id]);
        }
        $this->courses = Course::all();
        // $this->courses = Course::where('has_website', 1)->get();
    }

    public function render()
    {
        return view('livewire.admin.customers.requests');
    }

    public function delete($id)
    {

        $customer = User::where('id', $id)
            ->where('approved_by_referrer', true)
            ->where('approved_by_admin', false)
            ->first();

        if (!isset($customer))
            return $this->dispatch('failed_alert', ['title' => 'User Delete Failed']);

        User::where('parent_id', $customer->id)->forceDelete();
        $customer->forceDelete();
        $this->mount();
        return $this->dispatch('success_alert', ['title' => 'User successfully Deleted']);
    }

    /**
     * Trigger Payment Approval Alert
     */
    public function setStatus($id)
    {
        $this->selected_user_id = $id;
        return $this->dispatch('select_alert', ['title' => 'Do You Want To Approve Payment ?']);
    }

    /**
     * Set payment_type for approval
     */
    public function setStatusType($status)
    {
        $this->payment_type = User::USER_STATUS_LABLE[$status];
        $this->approve(id: $this->selected_user_id);
    }

    /**
     * Approve Payment
     * @param string $id
     */
    public function approve($id)
    {

        try {
            DB::beginTransaction();

            $user = User::where('id', $id)->first();
            $time = Carbon::now();
            $user->payment_status = User::PAYMENT_STATUS[$this->payment_type];
            $user->er_status = User::PAYMENT_STATUS[$this->payment_type];
            $user->approved_by_admin = true;
            $user->approved_at = $time;
            $password = $this->rand_string(10);
            $user->password =  Hash::make($password);


            $this->assigned_to = $user->assigned_user_id_on_approval;
            $this->assigned_user_side = $user->assigned_user_side_on_approval;

            $parent = User::where('id', $this->assigned_to)->first();
            if (!isset($parent))
                return $this->dispatch('failed_alert', ['title' => 'Invalid Parent']);

            $user->save();

            $availableUserOnAssignedPath = $this->getAvailableUserOnSelectedPath();

            $user->assigned_user_id = $availableUserOnAssignedPath->id;

            $parent =  $availableUserOnAssignedPath;
            if (!isset($parent))
                return $this->dispatch('failed_alert', ['title' => 'Invalid Parent']);

            $user->assigned_user_side = $this->assigned_user_side;

            $updatedUser = $this->setPath(user: $user, parent: $parent);
            $updatedUser->save();

            $user->dummy_a1_id = (new UserService())->updateLeftAccount(
                user: $this->getLeftSideUser(parent_id: $updatedUser->id),
                parent: $updatedUser,
                status: User::PAYMENT_STATUS[$this->payment_type],
                paymentStatus: $updatedUser->payment_status,
                time: $time
            )->id;

            $user->dummy_a2_id = (new UserService())->updateRightAccount(
                user: $this->getRightSideUser(parent_id: $updatedUser->id),
                parent: $updatedUser,
                status: User::PAYMENT_STATUS[$this->payment_type],
                paymentStatus: $updatedUser->payment_status,
                time: $time
            )->id;
            $user->save();

            $appliedCourse = UserPuchasedCourse::with('course')->where('user_id', $user->id)
                ->where('type', UserPuchasedCourse::TYPE['REFERRAL'])
                ->first();

            if (!isset($appliedCourse))
                abort(404);

            $ref_user = User::find($user->referrer_id);

            if ($ref_user->er_status == User::USER_STATUS['ER']) {

                /**
                 * Add Direct Comission to Referral
                 */
                $this->addDirectComission(
                    userId: $user->referrer_id,
                    amount: $appliedCourse->course?->referer_commission
                );
            }

            if (
                $user->points_disabled == 0 &&
                $user->payment_status = User::PAYMENT_STATUS['FULL'] &&
                $user->er_status = User::PAYMENT_STATUS['FULL']
            ) {
                /**
                 * Add points to Referrals
                 */
                (new PointsToReferralUsecase())->handle(user: $user, point: $appliedCourse->course?->course_point);
            }
            $approvedHistory = ApproveHistory::where('user_id', $user->id)->first();
            if (isset($approvedHistory)) {
                $approvedHistory->approved_admin_id = $user->approved_by;
                $approvedHistory->actual_assigned_id =  $user->assigned_to;
                $approvedHistory->actual_assigned_side =  $user->assigned_user_side;
                $approvedHistory->admin_approved_at = Carbon::now();
                $approvedHistory->save();
            } else {
                ApproveHistory::create([
                    'user_id' => $user->id,
                    'approved_admin_id' => $user->approved_by,
                    'actual_assigned_id' =>  $user->assigned_to,
                    'actual_assigned_side' =>  $user->assigned_user_side,
                    'admin_approved_at' => Carbon::now()
                ]);
            }

            DB::commit();

            $details['email'] = $user->email;
            $details['user_id'] = $user->id;
            $details['type'] = MailDetail::MAIL_TYPE['ADMIN_APPROVED'];
            $details['password'] = $password;
            $details['mobileNo'] = $user->mobile_no;
            $details['msg'] = $this->getApprovedSms(
                name: $user->first_name,

            );

            if (
                $user->payment_status != User::PAYMENT_STATUS['HALF'] &&
                $user->payment_status != User::PAYMENT_STATUS['PENDING']
            ) {

                dispatch(new SendMailJob($details));
                dispatch(new SendSmsJob($details));
            }


            $this->dispatch('success_alert', ['title' => 'User Approved Success']);
            return $this->mount();
        } catch (Exception $e) {

            DB::rollBack();
            Log::error($e->getMessage());

            return $this->dispatch('failed_alert', ['title' => 'User Approved Failed']);
        }
    }

    function rand_string($length)
    {

        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        return substr(str_shuffle($chars), 0, $length);
    }

    public function getAvailableUserOnSelectedPath()
    {
        $selectedUser = User::where('id', $this->assigned_to)->first();
        $selectedUserSide = $this->assigned_user_side;

        $ignoreSide = '';
        $parent = null;
        if ($selectedUserSide == 'A1') {

            $parent = $this->getAvaiableLeftParent(selectedUser: $selectedUser);
        }

        if ($selectedUserSide == 'A2') {

            $actualSelectedUser = User::where('parent_id', $this->assigned_to)
                ->where('type', User::USER_TYPE['RIGHT'])
                ->where('assigned_user_side', User::USER_TYPE['RIGHT'])
                ->first();

            if (!isset($actualSelectedUser)) {
                $actualSelectedUser = User::where('parent_id', $this->assigned_to)
                    ->where('type', User::USER_TYPE['MAIN'])
                    ->where('assigned_user_side', User::USER_TYPE['RIGHT'])
                    ->first();
            }

            if (isset($actualSelectedUser)) {
                $this->assigned_user_side = User::USER_TYPE['LEFT'];
                $parent = $this->getAvaiableLeftParent(selectedUser: $actualSelectedUser);
            } else {
                $actualSelectedUser = $selectedUser;
                $parent = $actualSelectedUser;
            }
        }

        return $parent;
    }

    public function getAvaiableLeftParent(User $selectedUser)
    {

        $pathId = 'P' . $selectedUser->unique_id;
        $pathId = $pathId . 'SL';
        $ignoreSide = 'SR';

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
            ->where('approved_by_admin', 1)
            ->get();

        $parent = null;
        if (sizeof($availableUsers) > 0) {
            foreach ($availableUsers as $user) {
                $pathAfterPathId = trim(substr($user->path, strpos($user->path, $pathId) + strlen($pathId)));

                if (sizeof($availableUsers) == 1 && $user->type == User::USER_TYPE['RIGHT']) {
                    $parent = $user;
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
        }
        if (!isset($parent)) {
            $parent = $selectedUser;
        }
        return $parent;
    }

    public function setPath(User $user, User $parent): User
    {
        $this->assigned_to = $user->assigned_user_id;

        if (!isset($parent))
            return $this->dispatch('failed_alert', ['title' => 'Invalid Parent']);

        $this->assigned_user_side = $user->assigned_user_side;

        $user->save();

        $mainParent = User::where('id', $parent->parent_id)->first();
        $sideToPath = null;

        if ($this->assigned_user_side == User::USER_TYPE['LEFT']) {

            $mainParent->my_left_a1_id = $user->id;
            $parent->dummy_a1_id = $user->id;
            $actualParent = $parent;
            $user->parent_id =  $actualParent->id;
            $sideToPath = 'SL';
        }

        if ($this->assigned_user_side == User::USER_TYPE['RIGHT']) {
            $mainParent->my_right_a1_id = $user->id;
            $parent->dummy_a2_id = $user->id;
            $actualParent  = $parent;
            $user->parent_id = $actualParent->id;
            $sideToPath = 'SR';
        }

        $user->path = $this->getMyPath(parentPath: $actualParent->path, myUniqueId: $user->unique_id, parentSide: $sideToPath);
        $mainParent->save();
        $user->save();
        $parent->save();

        return $user;
    }

    public function filter()
    {
        $this->ignore = false;
        if ($this->selected_course == 0) {

            $this->customers = User::with('referrer')
                ->leftjoin('user_puchased_courses', 'user_puchased_courses.user_id', 'users.id')
                ->leftjoin('courses', 'courses.id', 'user_puchased_courses.course_id')
                ->where('users.approved_by_admin', false)
                ->where('users.is_admin', false)
                ->whereNot('users.approved_referrer_id', NULL)
                ->where('users.approved_by_referrer', true)
                ->where('users.type', User::USER_TYPE['MAIN'])
                ->select(
                    'users.id',
                    'users.reg_no',
                    'users.mobile_no',
                    'users.er_status',
                    'users.payment_status',
                    'users.referrer_id',
                    'users.name',
                    'users.status',
                    'user_puchased_courses.user_id',
                    'user_puchased_courses.course_id',
                    'courses.name as course_name',
                )
                ->get();
        } else {



            $this->customers = User::with('referrer')
                ->leftjoin('user_puchased_courses', 'user_puchased_courses.user_id', 'users.id')
                ->leftjoin('courses', 'courses.id', 'user_puchased_courses.course_id')
                ->where('user_puchased_courses.course_id', $this->selected_course)
                ->where('users.approved_by_admin', false)
                ->where('users.is_admin', false)
                ->whereNot('users.approved_referrer_id', NULL)
                ->where('users.approved_by_referrer', true)
                ->where('users.type', User::USER_TYPE['MAIN'])
                ->select(
                    'users.id',
                    'users.reg_no',
                    'users.mobile_no',
                    'users.er_status',
                    'users.payment_status',
                    'users.referrer_id',
                    'users.name',
                    'users.status',
                    'user_puchased_courses.user_id',
                    'user_puchased_courses.course_id',
                    'courses.name as course_name',
                )
                ->get();
        }
    }

    public function changeCourseOfUser($userId)
    {

        $course = UserPuchasedCourse::where('user_id', $userId)
            ->where('type', UserPuchasedCourse::TYPE['REFERRAL'])
            ->first();

        $course->course_id = $this->user_selected_courses[$userId];
        $course->save();
        $this->mount();
        $this->dispatch('success_alert', ['title' => 'Course Updated Success']);
    }
}
