<?php

namespace App\Models\Marketplace;

use App\Livewire\Marketplace\Admin\Product\Category\Category;
use App\Models\ProductColor;
use App\Models\ProductSize;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';



    public function image(): HasOne
    {
        return $this->hasOne(ProductImage::class, 'product_id', 'id')->select('product_id', 'id', 'image_name');
    }

    public function category(): HasOne
    {
        return $this->hasOne(ProductCategory::class, 'id', 'category_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id')->select('product_id', 'id', 'image_name');
    }

    public function colors(): HasMany
    {
        return $this->hasMany(ProductColor::class, 'product_id', 'id');
    }

    public function sizes(): HasMany
    {
        return $this->hasMany(ProductSize::class, 'product_id', 'id');
    }
}
