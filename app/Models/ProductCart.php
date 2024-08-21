<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCart extends Model
{
    use HasFactory, SoftDeletes;


    public function productDetails()
    {
        return $this->belongsTo(ShopProduct::class, 'product_id', 'id');
    }
}
