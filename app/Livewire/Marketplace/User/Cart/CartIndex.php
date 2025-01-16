<?php

namespace App\Livewire\Marketplace\User\Cart;

use App\Livewire\Admin\Payment\Orders;
use App\Models\City;
use App\Models\Color;
use App\Models\CoupenCode;
use App\Models\DeliveryData;
use App\Models\Marketplace\Cart;
use App\Models\Marketplace\CartItem;
use App\Models\Marketplace\Order as MarketplaceOrder;
use App\Models\Marketplace\PaymentMode;
use App\Models\Marketplace\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductSize;
use App\Models\User;
use App\Traits\CartTrait;
use App\Traits\ComissionTrait;
use App\Traits\CourseTrait;
use App\Traits\WalletTrait;
use App\Usecases\Point\PointsToReferralUsecase;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CartIndex extends Component
{
    use CartTrait, WalletTrait, CourseTrait, ComissionTrait;

    public $cart, $user;
    public $is_checkout = false;
    public $first_name, $last_name,  $state, $city,
        $postal_code, $address_line_one,
        $address_line_two, $phone_number, $email;

    public $paymentModes, $selected_payment_mode, $total, $wallet;
    public $cities;
    public $coupen_applied = false, $coupen, $coupen_code;
    public $discount = 0, $final_total;
    public $delivery_data, $selected_delivery, $shipping_amount;

    public function mount()
    {
        $this->user = Auth::user();
        $cart_data =  $this->getCart(userId: $this->user->id);

        foreach ($cart_data->cartItems as $cart) {
            $attributes = json_decode($cart->attributes);

            if (isset($attributes->size_id)) {
                $size = ProductSize::find($attributes->size_id);
                $cart['size_detail'] = $size->name;
            }

            if (isset($attributes->color_id)) {
                $color = Color::find($attributes->color_id);
                $cart['color_detail'] = $color->name;
            }
        }

        $this->cart =   $cart_data;

        $this->paymentModes = PaymentMode::all();
        $this->wallet = $this->getWallet(userId: $this->user->id);
        $this->cities = City::all();
        $this->total = $this->cart ? $this->cart->total_price : 0;
        $this->final_total = $this->total;
        $this->delivery_data = DeliveryData::all();
    }

    public function render()
    {

        return view('livewire.marketplace.user.cart.cart-index');
    }

    public function checkout()
    {
        $this->is_checkout = true;
        // $this->place_order();
    }

    public function viewCart()
    {
        $this->is_checkout = false;
    }

    public function checkPaymentMode()
    {
        if (!isset($this->selected_payment_mode))
            return $this->dispatch('payment_failed_alert', ['title' => 'Please Select Payment Mode', 'content' => '']);

        if ($this->selected_payment_mode == 1) {

            if (!isset($this->wallet))
                return $this->dispatch('payment_failed_alert', ['title' => 'Insufficient Wallet', 'content' => 'You Cannot Use Wallet Due to Insufficient Wallet Balance']);

            if ($this->wallet->balance < $this->total)
                return $this->dispatch('payment_failed_alert', ['title' => 'Insufficient Wallet', 'content' => 'You Cannot Use Wallet Due to Insufficient Wallet Balance']);
        }

        $this->place_order();
        // $this->dispatch('payment_success_alert', ['title' => 'Successfully Updated']);

    }

    public function place_order()
    {
        $this->validate();
        if (
            !mb_strlen($this->first_name) > 0 ||
            !mb_strlen($this->last_name) > 0 ||
            !mb_strlen($this->city) > 0 ||
            !mb_strlen($this->postal_code) > 0 ||
            !mb_strlen($this->phone_number) > 0 ||
            !mb_strlen($this->selected_delivery) > 0 ||
            !mb_strlen($this->email) > 0
        ) {
            return $this->dispatch('payment_failed_alert', ['title' => 'Please Fill Billing Details']);
        }

        try {
            DB::beginTransaction();


            $order = new MarketplaceOrder();
            if (!empty($this->user->id)) {
                $order->user_id = trim($this->user->id);
            }
            $order->order_number = mt_rand(100000000, 999999999);
            $order->first_name = trim($this->first_name);
            $order->last_name = trim($this->last_name);
            // $order->company_name = trim($this->company_name);
            // $order->country_id = trim($this->country);
            // $order->state_id = trim($this->state);
            $order->city_id = trim($this->city);
            $order->postal_code = trim($this->postal_code);
            $order->address_one = trim($this->address_line_one);
            $order->address_two = trim($this->address_line_two);
            $order->phone = trim($this->phone_number);
            $order->email = trim($this->email);
            $order->note = ' ';
            $order->discount_amount = $this->discount ? $this->discount : 0;
            if (isset($coupen))
                $order->discount_id =  $this->coupen->id;
            $order->deliver_charge_id = trim($this->selected_delivery);
            $order->deliver_charge_amount = trim($this->shipping_amount);
            $order->total_amount = trim($this->final_total);
            $order->payment_method = trim($this->selected_payment_mode);

            if ($this->selected_payment_mode == 1) {

                $order->is_payment = 1;
            } else {
                $order->is_payment = 0;
            }
            // dd($this->city);
            $order->save();
            $ref_points = 0;
            foreach ($this->cart?->cartItems as $key => $cart) {
                $order_item = new OrderItem();
                $order_item->order_id = $order->id;
                $order_item->product_id = $cart->product_id;
                $order_item->quantity = $cart->quantity;
                $order_item->price = $cart->each;
                $ref_com = Product::where('id', $cart->product_id)->select('id', 'referrer_comission', 'item_points')->first();
                $attributes = json_decode($cart->attributes);
                if (isset($attributes->size_id)) {
                    $size = ProductSize::find($attributes->size_id);
                    $order_item->size_amount = $size->price;
                    $order_item->size_name = $size->name;
                }

                if (isset($attributes->color_id)) {
                    $color = Color::find($attributes->color_id);

                    $order_item->color_name = $color->name;
                }


                $order_item->total_price = $cart->each * $cart->quantity;
                $order->total_amount +=  $order_item->total_price;
                $order->total_comission +=  $ref_com ? $ref_com->referrer_comission : 0;
                $ref_points += $ref_com ? $ref_com->item_points : 0;
                $order_item->save();
            }


            $order->save();

            if ($this->selected_payment_mode == 1) {
                $this->payByWallet(
                    amount: $order->total_amount,
                    totalComission: $order->total_comission,
                    refPoints: $ref_points
                );
            }

            CartItem::where('cart_id', $this->cart->id)->delete();
            Cart::where('user_id', $this->user->id)->delete();
            DB::commit();
            $this->dispatch('success_alert', ['title' => 'Paid Successfully']);
            return redirect()->route('marketplace.user.index');
        } catch (Exception $e) {
            DB::rollback();
            return $this->dispatch('failed_alert', ['title' => 'Order Payment Failed']);
        }
    }

    protected function rules()
    {
        return  [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required',
            'city' => 'required',
            'postal_code' => 'required',
            'address_line_one' => 'required',


        ];
    }

    public function payByWallet(string $amount, string $totalComission, string $refPoints)
    {
        $user = Auth::user();

        if (!isset($user->referrer_id))
            $user = User::where('id', $user->parent_id)->first();


        /**
         * Add Direct Comission to Referral
         */
        $this->addDirectComission(
            userId: $user->referrer_id,
            amount: $totalComission
        );


        $this->deductFromWallet(amount: $amount);


        /**
         * Add points to Referrals
         */
        (new PointsToReferralUsecase())->handle(user: $user, point: $refPoints);
    }

    public function applyCoupen()
    {

        $coupen = CoupenCode::where('code', $this->coupen_code)->first();
        if (!isset($coupen)) {
            return $this->dispatch('failed_alert', ['title' => 'Invalid Coupen']);
        }
        if (Carbon::parse($coupen->expire_date) < Carbon::today()) {
            return $this->dispatch('failed_alert', ['title' => ' Coupen Expired']);
        }
        $this->coupen = $coupen;
        $this->coupen_code = $coupen->code;
        if ($coupen->type == 'percentage') {
            $this->discount = $this->total * $coupen->percent_amount / 100;
            $this->final_total = $this->total - $this->discount;
            $this->coupen_applied = true;
        }

        if ($coupen->type == 'fixed') {
            $this->discount =  $coupen->percent_amount;
            $this->final_total = $this->total - $this->discount;
            $this->coupen_applied = true;
        }
    }

    public function setDeliveryMethod()
    {
        $delivery = DeliveryData::where('id', $this->selected_delivery)->first();
        if (!isset($delivery)) {
            return $this->dispatch('failed_alert', ['title' => 'Invalid Delivery Method']);
        }
        $this->shipping_amount = $delivery->price;
        $this->final_total = $this->total - $this->discount + $delivery->price;
    }
}
