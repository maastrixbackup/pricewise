<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerCredential extends Model
{
    use HasFactory;
    protected $fillable = ['postal_code', 'user_id', 'category', 'service_details', 'order_status', 'house_no'];
}
