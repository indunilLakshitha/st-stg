<?php

namespace App\Livewire;

use App\Models\MasterData;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletHistory;
use App\Traits\LabelTrait;
use App\Traits\MyTeamTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    use LabelTrait, MyTeamTrait;

    public $user;
    public $referral_link;
    public $totalComission, $totalDirectSaleComission, $totalGroupSaleComission;
    public $totalPaidComission, $totalUnpaidComission, $registrationReqests, $registrationRequests;
    public $userStatus;

    public $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'Augest', 'September', 'October', 'November', 'December'],
        $ir_counts = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
        $ir_paid = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
        $income_amount = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
        $income_paid_amount = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

    public $directEarnings = 0, $groupEarnings = 0, $totalEarning = 0, $totalWithdrawals = 0, $accountBalance = 0;

    public function mount()
    {
        $this->user = Auth::user();
        if ($this->user->type == User::USER_TYPE['MAIN']) {
            $this->referral_link = env('APP_URL') . '/register/' . $this->user->reg_no;
        } else {
            $parent = User::where('id', $this->user->parent_id)->select('id', 'reg_no')->first();
            $this->referral_link = env('APP_URL') . '/register/' . $parent->reg_no;
        }

        if ($this->user->is_admin) {
            $defaultParent = User::find(MasterData::first()->default_parent_id);
            $this->referral_link = env('APP_URL') . '/register/' . $defaultParent->reg_no;
        }

        $comissions = WalletHistory::where('user_id', $this->user->id)
            ->get();

        if ($this->user->is_admin) {
            $this->totalComission = $this->calTotalComission($comissions);

            $this->totalDirectSaleComission = $this->calTotalDirectSaleComission($comissions);

            $this->totalGroupSaleComission = $this->calTotalGroupSaleComission($comissions);

            $this->totalUnpaidComission = $this->calTotalUnpaidComission($comissions);

            $this->totalPaidComission = $this->calTotalPaidComission($comissions);
        } else {

            $this->directEarnings = $this->calDirectEarnings($comissions);
            $this->groupEarnings = $this->calGroupEarnings($comissions);
            $this->totalEarning = $this->directEarnings +   $this->groupEarnings;
            $this->totalWithdrawals = $this->calTotalWithdrawals($comissions);
            $wallet = Wallet::where('user_id', $this->user->id)->first();

            if (!isset($wallet)) {
                $this->accountBalance = 0;
            } else {
                $this->accountBalance =  $wallet->balance;
            }
        }
        $this->registrationRequests = User::where('approved_by_admin', false)
            ->where('parent_id', NULL)
            ->where('referrer_id', '!=', NULL)
            ->where('assigned_user_id', NULL)
            ->where('approved_referrer_id', NULL)
            ->where('approved_by_referrer', false)
            ->where('referrer_id', Auth::user()->reg_no)
            ->select('id', 'reg_no', 'mobile_no', 'payment_status', 'referrer_id', 'name')
            ->count();

        $this->userStatus = $this->calUserStatus($this->getMyFullTeam(userId: $this->user->id));

        if ($this->user->is_admin) {
            $this->getAdminIncome();
            $this->getRegistrationRecords();
        } else {
            $this->getUserIncome();
        }
    }

    public function getAdminIncome()
    {
        $data =  WalletHistory::select(
            DB::raw('DATE_FORMAT(created_at, "%M") as month_name'),
            DB::raw('SUM(amount) as amount')
        )
            ->where('type', WalletHistory::TYPE['ADDED'])
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month_name')
            ->orderBy('id')
            ->get();

        foreach ($data as $mnth) {

            $index = array_search($mnth->month_name, $this->months);
            $this->income_amount[$index] = $mnth->amount;
        }

        $paid_data =  WalletHistory::select(
            DB::raw('DATE_FORMAT(paid_at, "%M") as month_name'),
            DB::raw('SUM(amount) as amount')
        )
            ->where('status', WalletHistory::STATUS['TRANSFERED'])
            ->whereNotNull('paid_at')
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month_name')
            ->orderBy('id')
            ->get();

        foreach ($paid_data as $mnth) {
            $index = array_search($mnth->month_name, $this->months);
            $this->income_paid_amount[$index] = $mnth->amount;
        }
    }

    public function getUserIncome()
    {
        $data =  WalletHistory::select(
            DB::raw('DATE_FORMAT(created_at, "%M") as month_name'),
            DB::raw('SUM(amount) as amount')
        )
            ->where('type', WalletHistory::TYPE['ADDED'])
            ->where('user_id', Auth::user()->id)
            ->groupBy('month_name')
            ->whereYear('created_at', Carbon::now()->year)
            ->orderBy('id')
            ->get();

        foreach ($data as $mnth) {

            $index = array_search($mnth->month_name, $this->months);
            $this->income_amount[$index] = $mnth->amount;
        }

        $paid_data =  WalletHistory::select(
            DB::raw('DATE_FORMAT(paid_at, "%M") as month_name'),
            DB::raw('SUM(amount) as amount')
        )
            ->where('status', WalletHistory::STATUS['TRANSFERED'])
            ->whereNotNull('paid_at')
            ->where('user_id', Auth::user()->id)
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month_name')
            ->orderBy('id')
            ->get();

        foreach ($paid_data as $mnth) {
            $index = array_search($mnth->month_name, $this->months);
            $this->income_paid_amount[$index] = $mnth->amount;
        }
    }

    public function getRegistrationRecords()
    {
        $data =  User::select(
            DB::raw('DATE_FORMAT(created_at, "%M") as month_name'),
            DB::raw('count(id) as count')
        )
            ->where('type', User::USER_TYPE['MAIN'])
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month_name')
            ->orderBy('id')
            ->get();

        foreach ($data as $mnth) {
            $index = array_search($mnth->month_name, $this->months);
            $this->ir_counts[$index] = $mnth->count;
        }

        $data =  User::select(
            DB::raw('DATE_FORMAT(created_at, "%M") as month_name'),
            DB::raw('count(id) as count')
        )
            ->where('type', User::USER_TYPE['MAIN'])
            ->where('paid', true)
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month_name')
            ->orderBy('id')
            ->get();

        foreach ($data as $mnth) {
            $index = array_search($mnth->month_name, $this->months);
            $this->ir_paid[$index] = $mnth->count;
        }
    }

    public function render()
    {
        return view('livewire.dashboard');
    }

    public function triggerCopy()
    {
        return $this->dispatch('copy_alert', ['title' => 'Successfully Copied']);
    }

    public function calUserStatus($users)
    {
        $data = [
            'NONE' => 0,
            'HALF' => 0,
            'FULL' => 0,
            'ER' => 0,
        ];
        foreach ($users as $user) {
            if ($user->er_status == 1)
                $data['NONE'] += 1;
            if ($user->er_status == 2)
                $data['HALF'] += 1;
            if ($user->er_status == 3)
                $data['FULL'] += 1;
            if ($user->er_status == 4)
                $data['ER'] += 1;
        }

        return $data;
    }

    public function calTotalComission($data)
    {
        $total = 0;
        foreach ($data as $comission) {
            if (
                $comission->type == WalletHistory::TYPE['ADDED']
            )
                $total += $comission->amount;
        }

        return $total;
    }

    public function calDirectEarnings($data)
    {
        $total = 0;
        foreach ($data as $comission) {
            if (
                $comission->type == WalletHistory::TYPE['ADDED'] &&
                $comission->comission_type == WalletHistory::COMISSION_TYPES['DIRECT']
            )
                $total += $comission->amount;
        }

        return $total;
    }

    public function calGroupEarnings($data)
    {
        $total = 0;
        foreach ($data as $comission) {
            if (
                $comission->type == WalletHistory::TYPE['ADDED'] &&
                $comission->comission_type == WalletHistory::COMISSION_TYPES['GSC']
            )
                $total += $comission->amount;
        }

        return $total;
    }

    public function calTotalWithdrawals($data)
    {
        $total = 0;
        foreach ($data as $comission) {
            if (
                $comission->status == WalletHistory::STATUS['TRANSFERED']
            )
                $total += $comission->amount;
        }

        return $total;
    }

    public function calTotalDirectSaleComission($data)
    {
        $total = 0;
        foreach ($data as $comission) {
            if (
                $comission->type == WalletHistory::TYPE['ADDED'] &&
                $comission->comission_type == WalletHistory::COMISSION_TYPES['DIRECT']
            )
                $total += $comission->amount;
        }

        return $total;
    }

    public function calTotalGroupSaleComission($data)
    {
        $total = 0;
        foreach ($data as $comission) {
            if (
                $comission->type == WalletHistory::TYPE['ADDED'] &&
                $comission->comission_type == WalletHistory::COMISSION_TYPES['GSC']
            )
                $total += $comission->amount;
        }
        return $total;
    }

    public function calTotalUnpaidComission($data)
    {
        $total = 0;
        foreach ($data as $comission) {
            if (
                $comission->type == WalletHistory::TYPE['REMOVED'] &&
                $comission->status == WalletHistory::STATUS['REQUESTED']
            )
                $total += $comission->amount;
        }
        return $total;
    }
    public function calTotalPaidComission($data)
    {
        $total = 0;
        foreach ($data as $comission) {
            if (
                $comission->type == WalletHistory::TYPE['REMOVED'] &&
                $comission->status == WalletHistory::STATUS['TRANSFERED']
            )
                $total += $comission->amount;
        }
        return $total;
    }
}
