<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'provider_id',
        'user_type',
        'category',
        'sub_category',
        'service_id',
        'service_type',
        'combos',
        'advantages',
        'postal_code',
        'total_price',
        'discounted_price',
        'discounted_prct',
        'commission_amt',
        'commission_prct',
        'solar_panels',
        'no_gas',
        'request_status',
        'shipping_address',
        'billing_address',
        'order_no',
        'contact_details',
        'additional_information',
        'additional_questions',
        'delivery',
        'verification'
    ];

    protected $casts = [
        'contact_details' => 'array',
        'additional_information' => 'array',
        'additional_questions' => 'array',
        'delivery' => 'array',
        'verification' => 'array',
    ];


    public function service()
    {
        return $this->morphTo();
    }

    public function userDetails()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function providerDetails()
    {
        return $this->belongsTo(Provider::class, 'provider_id', 'id');
    }
    public function categoryDetails()
    {
        return $this->belongsTo(Category::class, 'category', 'id');
    }
    public function advantagesData()
    {
        return $this->hasMany(PostRequest::class, 'request_id', 'id');
    }
    public function feedInCost()
    {
        return $this->hasOne(FeedInCost::class, 'provider', 'provider_id');
    }
}
