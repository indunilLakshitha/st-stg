<?php

namespace App\Livewire\Marketplace\Comp;

use App\Models\Marketplace\ProductCategory;
use App\Traits\CartTrait;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Header extends Component
{
    use CartTrait;

    public $cart;

    protected $listeners = ['cart_updated' => 'handleCartUpdate'];
    public $categories;

    public function mount()
    {
        if (Auth::check()) {

            $user = Auth::user();
            $this->cart =  $this->getCart(userId: $user->id);
        }
        $this->categories = ProductCategory::with('sub')->get();
        // dd($this->cart);
    }

    public function render()
    {
        return view('livewire.marketplace.comp.header');
    }

    public function handleCartUpdate()
    {
        $this->mount();
    }
}
