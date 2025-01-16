<?php

namespace App\Livewire\Reports;

use App\Models\User;
use App\Models\WalletHistory;
use App\Traits\WalletTrait;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class IncomeReport extends Component
{
    use WalletTrait;

    public $comissions;
    public   $date_from,
        $date_to,
        $selected_payment_status,
        $selected_comission_type, $selected_account_type;


    public function mount()
    {
        $this->comissions =  WalletHistory::join('users', 'users.id', 'wallet_histories.user_id')
            ->select('wallet_histories.*', 'users.id as user_id', 'users.first_name', 'users.reg_no', 'users.type as user_type')
            ->where('wallet_histories.type', WalletHistory::TYPE['ADDED'])
            ->where('wallet_histories.user_id', Auth::user()->id)
            ->orderBy('wallet_histories.id', 'desc')
            ->get();
    }
    public function render()
    {
        return view('livewire.reports.income-report');
    }

    public function filter()
    {

        $comissions =  WalletHistory::join('users', 'users.id', 'wallet_histories.user_id')
            ->select('wallet_histories.*', 'users.id', 'users.first_name', 'users.reg_no', 'users.type as user_type')
            ->where('wallet_histories.type', WalletHistory::TYPE['ADDED'])
            ->where('wallet_histories.user_id', Auth::user()->id)
            ->orderBy('wallet_histories.id', 'desc');

        $comissions = $comissions->when($this->date_from, function ($q) {
            return $q->whereDate('wallet_histories.created_at', '>=', $this->date_from);
        });

        $comissions = $comissions->when($this->date_to, function ($q) {
            return $q->whereDate('wallet_histories.created_at', '<=', $this->date_to);
        });


        $comissions = $comissions->when($this->selected_comission_type, function ($q) {
            return $q->where('wallet_histories.comission_type', $this->selected_comission_type);
        });

        $comissions = $comissions->when($this->selected_account_type, function ($q) {

            if ($this->selected_account_type == 1) {
                return $q->where('users.type', User::USER_TYPE['MAIN']);
            }

            if ($this->selected_account_type == 2) {
                return $q->where('users.type', User::USER_TYPE['LEFT'])
                    ->orWhere('users.type', User::USER_TYPE['RIGHT']);
            }
        });

        // dd($comissions);
        $this->comissions =  $comissions->get();
    }
}
