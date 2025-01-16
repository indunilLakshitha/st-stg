<?php

namespace App\Livewire\Admin\Deposited;

use App\Models\WalletHistory;
use App\Traits\WalletTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Pending extends Component
{
    use WalletTrait;

    public $pendings = [], $comissions;
    public $total_direct_sale = 0, $total_group_sale = 0, $total_amount = 0;
    public $total_withdrawal_requests = 0, $total_pending_deposites = 0, $total_deposites = 0;
    public $selected_bank;

    public function mount()
    {
        $pendings = WalletHistory::with('user.bank', 'wallet')
            // ->where('status', WalletHistory::STATUS['REQUESTED'])
            ->where('type', WalletHistory::TYPE['REMOVED'])
            ->get();

        // $this->comissions = WalletHistory::join('users', 'users.id', 'wallet_histories.user_id')
        //     ->select('wallet_histories.*', 'users.id', 'users.first_name', 'users.reg_no', 'users.type as user_type')
        //     ->where('wallet_histories.type', WalletHistory::TYPE['ADDED'])
        //     ->orderBy('wallet_histories.id', 'desc')
        //     ->get();


        // $this->total_direct_sale = $this->getDirectSale();
        // $this->total_group_sale = $this->getGroupSale();
        // $this->total_amount =  $this->total_direct_sale + $this->total_group_sale;

        if (isset($pendings)) {
            $this->pendings = $this->getPEndings($pendings);
            $this->total_withdrawal_requests = $this->getTotalWithdrawalRequests($pendings);
            $this->total_pending_deposites = $this->getTotalPendingDeposites($pendings);
            $this->total_deposites = $this->getTotalDeposites($pendings);
        }
    }

    public function render()
    {
        return view('livewire.admin.deposited.pending');
    }

    public function getPEndings($data)
    {
        $result = [];
        foreach ($data as $pending) {
            if (
                $pending->status == WalletHistory::STATUS['REQUESTED']
            )
                array_push($result, $pending);
        }

        return collect($result);
    }

    public function getTotalWithdrawalRequests($data)
    {
        $total = 0;
        foreach ($data as $pending) {

            $total += $pending->amount;
        }
        return $total;
    }

    public function getTotalPendingDeposites($data)
    {
        $total = 0;
        foreach ($data as $pending) {
            if (
                $pending->status == WalletHistory::STATUS['REQUESTED']
            )
                $total += $pending->amount;
        }

        return $total;
    }

    public function getTotalDeposites($data)
    {
        $total = 0;
        foreach ($data as $pending) {
            if (
                $pending->status == WalletHistory::STATUS['TRANSFERED']
            )
                $total += $pending->amount;
        }

        return $total;
    }

    public function approve($id)
    {

        $this->setToPaid(walletHistoryId: $id);
        $this->mount();
    }

    public function cansel($id)
    {
        $this->setToCansel(walletHistoryId: $id);
        $this->mount();

        return $this->dispatch('success_alert', ['title' => 'Payment successfully Canseled']);
    }



    public function getDirectSale()
    {
        $total = 0;
        foreach ($this->comissions as $comission) {
            if ($comission->comission_type == WalletHistory::COMISSION_TYPES['DIRECT']) {
                $total += $comission->amount;
            }
        }

        return $total;
    }

    public function getGroupSale()
    {
        $total = 0;
        foreach ($this->comissions as $comission) {
            if ($comission->comission_type == WalletHistory::COMISSION_TYPES['GSC']) {
                $total += $comission->amount;
            }
        }

        return $total;
    }

    public function copyBankDetails($bank, $type)
    {
        $text = "";
        switch ($type) {
            case 1:
                $text = $bank['holder_name'];
                break;
            case 2:
                $text = $bank['account_number'];
                break;
            case 3:
                $text = $bank['bank_name'];
                break;
            case 4:
                $text = $bank['branch'];
                break;
        }
        return $this->dispatch('bank_set', ['text' => $text]);
    }
}
