<?php

namespace App\Livewire\Admin\Deposited;

use App\Models\WalletHistory;
use Livewire\Component;

class Deposited extends Component
{

    public $pendings;

    public function mount()
    {
        $this->pendings = WalletHistory::with('user','admin')
            ->where('status', WalletHistory::STATUS['TRANSFERED'])
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.deposited.deposited');
    }
}
