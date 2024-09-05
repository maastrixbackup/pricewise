<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComboProduct extends Model
{
    use HasFactory;

    public function comboProductDetails()
    {
        return $this->belongsTo(ShopProduct::class, 'deal_id', 'id');
    }
}
