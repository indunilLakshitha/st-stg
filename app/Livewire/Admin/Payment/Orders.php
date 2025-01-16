<?php

namespace App\Livewire\Admin\Payment;

use App\Models\UserPuchasedCourse;
use Livewire\Component;

class Orders extends Component
{

    public $orders;

    public function mount()
    {
        $this->orders = UserPuchasedCourse::with('course', 'user')
            ->where('type', UserPuchasedCourse::TYPE['DIRECT'])
            ->get();
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

    public function render()
    {
        return view('livewire.admin.payment.orders');
    }
}
