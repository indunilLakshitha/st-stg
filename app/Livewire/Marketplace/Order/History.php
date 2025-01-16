<?php

namespace App\Livewire\Marketplace\Order;

use App\Models\Marketplace\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class History extends Component
{

    public $orders;

    public function mount()
    {
        $user = Auth::user();
        $this->orders = Order::where('user_id', $user->id)->get();
    }

    public function render()
    {
        return view('livewire.marketplace.order.history');
    }
}
