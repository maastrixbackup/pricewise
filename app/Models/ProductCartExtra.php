<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCartExtra extends Model
{
    use HasFactory, SoftDeletes;
    // protected $fillable = ['cart_id', 'ext_id', 'product_id', 'amount'];

}
