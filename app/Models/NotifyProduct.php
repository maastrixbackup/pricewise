<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotifyProduct extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'product_id',
        'notify_email',
        'curr_time',
        'viewed',
        'notified',
    ];


    public function userDetails()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function productDetails()
    {
        return $this->belongsTo(ShopProduct::class, 'product_id', 'id');
    }
}
