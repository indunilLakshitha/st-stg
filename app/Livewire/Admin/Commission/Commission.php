<?php

namespace App\Livewire\Admin\Commission;

use App\Models\ComissionHistory;
use App\Models\User;
use App\Models\WalletHistory;
use App\Traits\WalletTrait;
use Livewire\Component;
use Livewire\WithPagination;

class Commission extends Component
{
    use WalletTrait, WithPagination;

    public  $filtered_comissions;
    public   $date_from, $search,
        $date_to,
        $selected_payment_status,
        $selected_account_type,
        $selected_comission_type;

    public $total_direct_sale = 0, $total_group_sale = 0, $total_amount = 0;
    protected $queryString = ['date_from', 'date_to', 'selected_account_type', 'search'];

    public function mount() {}

    public function render()
    {
        $comissions = WalletHistory::join('users', 'users.id', 'wallet_histories.user_id')
            ->select('wallet_histories.*', 'users.id', 'users.first_name', 'users.reg_no', 'users.type as user_type')
            ->where('wallet_histories.type', WalletHistory::TYPE['ADDED'])
            ->where('wallet_histories.comission_type', '!=', WalletHistory::COMISSION_TYPES['DUMMY_TRANSFERED'])
            ->orderBy('wallet_histories.id', 'desc')
            ->orderBy('users.id', 'asc')
            ->when($this->date_from, function ($q) {
                return $q->whereDate('wallet_histories.created_at', '>=', $this->date_from);
            })
            ->when($this->date_to, function ($q) {
                return $q->whereDate('wallet_histories.created_at', '<=', $this->date_to);
            })
            ->when($this->search, function ($q) {
                if (strlen($this->search) >= 3) {
                    return $q->where('users.first_name', 'like', '%' . $this->search . '%')
                        ->orWhere('users.last_name', 'like', '%' . $this->search . '%')
                        ->orWhere('users.name', 'like', '%' . $this->search . '%')
                        ->orWhere('users.id', 'like', '%' . $this->search . '%')
                        ->orWhere('users.nic', 'like', '%' . $this->search . '%');
                }
            })
            ->when($this->selected_account_type, function ($q) {

                if ($this->selected_account_type == 1) {
                    return $q->where('users.type', User::USER_TYPE['MAIN']);
                }

                if ($this->selected_account_type == 2) {
                    return $q->where('users.type', User::USER_TYPE['LEFT'])
                        ->orWhere('users.type', User::USER_TYPE['RIGHT']);
                }
            })
            ->paginate(15);

        // $this->total_direct_sale = $this->getDirectSale();
        // $this->total_group_sale = $this->getGroupSale();
        // $this->total_amount =  $this->total_direct_sale + $this->total_group_sale;
        return view('livewire.admin.commission.commission', ['comissions' => $comissions]);
    }

    public function setPaid($id)
    {

        $this->setToPaid(walletHistoryId: $id);
        $this->mount();

        return $this->dispatch('success_alert', ['title' => 'Payment successfully Updated']);
    }

    public function filter() {}

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
}
