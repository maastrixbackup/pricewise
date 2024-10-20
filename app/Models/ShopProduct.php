<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopProduct extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function categoryDetails()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id', 'id');
    }

    public function brandDetails()
    {
        return $this->belongsTo(ProductBrand::class, 'brand_id', 'id');
    }

    public function colorDetails()
    {
        return $this->belongsTo(ProductColor::class, 'color_id', 'id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }
}
