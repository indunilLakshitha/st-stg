<?php

namespace App\Livewire\Order;

use App\Models\User;
use App\Models\UserPuchasedCourse;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class OrderHistory extends Component
{
    public $orders;

    public function mount()
    {
        $user = Auth::user();
        $myCode = 'P' . $user->unique_id;
        $myTeam = User::where('approved_by_admin', true)
            ->where('path', 'like', '%' .  $myCode . '%')
            ->pluck('id');

        $this->orders = UserPuchasedCourse::with('course', 'user')
            ->whereIn('user_id', $myTeam)
            ->where('type', UserPuchasedCourse::TYPE['DIRECT'])
            ->get();
    }

    public function render()
    {
        return view('livewire.order.order-history');
    }

    public function approve($id)
    {
        $order =  UserPuchasedCourse::where('type', UserPuchasedCourse::TYPE['DIRECT'])
            ->where('id', $id)
            ->first();
        $order->status = UserPuchasedCourse::STATUS['APPROVED'];
        $order->save();
        $this->mount();
        return $this->dispatch('success_alert', ['title' => 'Order Approved Success']);
    }

    public function cansel($id)
    {
        $order =  UserPuchasedCourse::where('type', UserPuchasedCourse::TYPE['DIRECT'])
            ->where('id', $id)
            ->first();
        $order->status = UserPuchasedCourse::STATUS['CANSELED'];
        $order->save();
        $this->mount();
        return $this->dispatch('success_alert', ['title' => 'Order Canseled Success']);
    }
}
