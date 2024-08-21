<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductRating extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function productDetails() {
        return $this->belongsTo(ShopProduct::class, 'product_id', 'id');
    }

    public function userDetails() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
