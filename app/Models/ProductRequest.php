<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ProductRequest extends Model
{
    use HasFactory, SoftDeletes;

    public function userDetails()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function productDetails()
    {
        return $this->belongsTo(ShopProduct::class, 'product_id', 'id');
    }
}
