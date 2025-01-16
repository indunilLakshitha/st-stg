<?php

namespace App\Livewire\Marketplace\User;

use App\Models\Marketplace\Product;
use App\Models\ProductSize;
use App\Traits\CartTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Item extends Component
{
    use CartTrait;

    public $item, $image_url, $quantity = 1;
    public $image_url_2 = null, $image_url_ = null, $image_url_3 = null, $image_url_4 = null;
    public $color_id, $size_id;
    public $item_price, $total_price;


    public function mount($id)
    {
        $this->item = Product::with('images', 'colors.color', 'sizes')
            ->where('slug', $id)
            // ->where('id', $id)
            ->first();
        if (!isset($this->item)) {
            abort(404);
        }
        $this->total_price = $this->item->price;
        if (sizeof($this->item?->images) == 1) {
            $this->image_url = env('MARKET_APP_URL') . '/upload/product/' . $this->item?->images[0]?->image_name;
        } else if (sizeof($this->item?->images) == 2) {
            $this->image_url = env('MARKET_APP_URL') . '/upload/product/' . $this->item?->images[0]?->image_name;
            $this->image_url_2 = env('MARKET_APP_URL') . '/upload/product/' . $this->item?->images[1]?->image_name;
        } else if (sizeof($this->item?->images) == 3) {
            $this->image_url = env('MARKET_APP_URL') . '/upload/product/' . $this->item?->images[0]?->image_name;
            $this->image_url_2 = env('MARKET_APP_URL') . '/upload/product/' . $this->item?->images[1]?->image_name;
            $this->image_url_3 = env('MARKET_APP_URL') . '/upload/product/' . $this->item?->images[2]?->image_name;
        } else if (sizeof($this->item?->images) == 4) {
            $this->image_url = env('MARKET_APP_URL') . '/upload/product/' . $this->item?->images[0]?->image_name;
            $this->image_url_2 = env('MARKET_APP_URL') . '/upload/product/' . $this->item?->images[1]?->image_name;
            $this->image_url_3 = env('MARKET_APP_URL') . '/upload/product/' . $this->item?->images[2]?->image_name;
            $this->image_url_4 = env('MARKET_APP_URL') . '/upload/product/' . $this->item?->images[3]?->image_name;
        }
    }

    public function render()
    {
        return view('livewire.marketplace.user.item');
    }

    public function processAddToCart($productId)
    {

        try {
            DB::beginTransaction();

            $cart = $this->addToCart();
            $product = Product::where('id', $productId)->first();
            if (!isset($product)) {
                return redirect()->route('marketplace.user.index');
            }
            $price = 0;
            $size = ProductSize::find($this->size_id);
            if (isset($size)) {
                $price = $size->price;
            } else {
                $price = $product->price;
            }
            $this->addToCartItemsWithQty(
                cart: $cart,
                productId: $productId,
                qty: $this->quantity,
                price: $price,
                color: $this->color_id,
                size: $this->size_id
            );

            DB::commit();
            $this->mount($this->item->slug);
            $this->dispatch('cart_updated');
            $this->dispatch('success_alert', ['title' => 'Cart successfully Updated']);
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatch('failed_alert', ['title' => 'Failed']);
        }
    }

    public function setColor($id)
    {
        $this->color_id = $id;
    }

    public function selectSize()
    {
        $size = ProductSize::where('id', $this->size_id)->first();
        if (isset($size) && isset($size->price)) {

            $this->total_price = $size->price * $this->quantity;
            $this->item_price = $size->price;
        }
    }

    public function setQty()
    {
        $this->total_price =  $this->item_price * $this->quantity;
    }
}
