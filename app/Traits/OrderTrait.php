<?php

namespace App\Traits;

use App\Models\Marketplace\Order;
use Illuminate\Support\Facades\Auth;

trait OrderTrait
{
    public function addToOrder(): Order
    {
        $order = new Order();
        if (!empty($user_id)) {
            $order->user_id = trim($user_id);
        }
        $order->order_number = mt_rand(100000000, 999999999);
        $order->first_name = trim($request->first_name);
        $order->last_name = trim($request->last_name);
        $order->company_name = trim($request->company_name);
        $order->country_id = trim($request->country);
        $order->state_id = trim($request->state);
        $order->city_id = trim($request->city);
        $order->postal_code = trim($request->postal_code);
        $order->address_one = trim($request->address_line_one);
        $order->address_two = trim($request->address_line_two);
        $order->phone = trim($request->phone_number);
        $order->email = trim($request->email);
        $order->note = trim($request->note);
        $order->discount_amount = trim($discount_amount);
        $order->discount_id = $request->discount_code ? $getDiscount->id : null;
        $order->deliver_charge_id = trim($request->deliver);
        $order->deliver_charge_amount = trim($shipping_amount);
        $order->total_amount = trim($total_amount);
        $order->payment_method = trim($request->payment_method);

        $order->save();
        return $order;
    }
}
