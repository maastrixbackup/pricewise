<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopOrder extends Model
{
    use HasFactory;

    // Define the fillable attributes
    protected $fillable = [
        'user_id',
        'order_number',
        'user_type',
        'salutation',
        'guest_fname',
        'guest_lname',
        'guest_phone',
        'guest_email',
        'total_amount',
        'order_date',
        'exp_delivery',
        'bill_different',
        'shipping_address',
        'company_details',
        'billing_address'
    ];
}
