<?php

namespace App\Models\Marketplace;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;
    protected $table = 'cart';

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class, 'cart_id', 'id');
    }
}
