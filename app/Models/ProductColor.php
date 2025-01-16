<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProductColor extends Model
{
    use HasFactory;

    protected $table = 'product_colors';

    public function color(): HasOne
    {
        return $this->hasOne(Color::class, 'id', 'color_id');
    }
}
