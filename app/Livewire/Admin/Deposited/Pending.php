<?php

namespace App\Livewire\Admin\Deposited;

use App\Models\WalletHistory;
use App\Traits\WalletTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Pending extends Component
{
    use WalletTrait, WithPagination;

    public  $comissions;
    public $total_direct_sale = 0, $total_group_sale = 0, $total_amount = 0;
    public $total_withdrawal_requests = 0, $total_pending_deposites = 0, $total_deposites = 0;
    public $selected_bank;
    public $status = WalletHistory::STATUS['REQUESTED'], $from, $to;

    protected $queryString = ['status', 'from', 'to'];

    public function mount() {}

    public function render()
    {
        $all_pendings = WalletHistory::with('user.bank', 'wallet')
            ->where('type', WalletHistory::TYPE['REMOVED'])
            ->when($this->status, function ($q) {
                $q->where('status', $this->status);
            })
            ->when($this->from, function ($q) {
                $q->whereDate('requested_at', '>=', $this->from);
            })
            ->when($this->to, function ($q) {
                $q->whereDate('requested_at', '<=', $this->to);
            })
            ->get();

        $pendings =  WalletHistory::with('user.bank', 'wallet')
            ->where('type', WalletHistory::TYPE['REMOVED'])
            ->when($this->status, function ($q) {
                $q->where('status', $this->status);
            })
            ->when($this->from, function ($q) {
                $q->whereDate('requested_at', '>=', $this->from);
            })
            ->when($this->to, function ($q) {
                $q->whereDate('requested_at', '<=', $this->to);
            })
            ->paginate(10);

        if (isset($all_pendings)) {
            $this->total_withdrawal_requests = $this->getTotalWithdrawalRequests($all_pendings);
            $this->total_pending_deposites = $this->getTotalPendingDeposites($all_pendings);
            $this->total_deposites = $this->getTotalDeposites($all_pendings);
        }
        return view('livewire.admin.deposited.pending', ['pendings' => $pendings]);
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

    public function  filter() {}
}
