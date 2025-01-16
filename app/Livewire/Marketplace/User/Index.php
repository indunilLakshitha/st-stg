<?php

namespace App\Livewire\Marketplace\User;

use App\Livewire\Marketplace\Admin\Product\Product\Product;
use App\Models\Marketplace\Product as MarketplaceProduct;
use App\Models\Marketplace\ProductCategory;
use App\Traits\CartTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Index extends Component
{
    use CartTrait;

    public $categories, $products, $min_max;
    public $minPrice = 10;
    public $maxPrice = 50;


    public function mount($cat = null, $maxPrice = null, $minPrice = null)
    {


        if (isset($cat) && strlen($cat) > 0) {
            $this->products = MarketplaceProduct::with('image', 'category')
                ->whereHas(
                    'category',
                    function ($q) use ($cat) {
                        $q->where('categories.slug', $cat);
                    }
                )

                ->get();
        } else {
            $this->products = MarketplaceProduct::with('image')->get();
        }
        $this->categories = ProductCategory::with('sub')->withCount('products')->get();
        foreach ($this->products as $product) {
            $product['image_url'] = env('MARKET_APP_URL') . '/upload/product/' . $product?->image?->image_name;
        }
    }

    public function render()
    {
        return view('livewire.marketplace.user.index');
    }

    public function getMinMax()
    {
        dd($this->min_max);
    }

    public function processAddToCart($productId)
    {

        try {
            DB::beginTransaction();

            $cart = $this->addToCart();
            $this->addToCartItems(cart: $cart, productId: $productId);

            DB::commit();
            $this->mount();
            $this->dispatch('cart_updated');
            $this->dispatch('success_alert', ['title' => 'Cart successfully Updated']);
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatch('failed_alert', ['title' => 'Cart successfully Updated']);
        }
    }
}
