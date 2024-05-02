<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTime;

class TvInternetProduct extends Model
{
    use HasFactory;
    protected $table = "tv_internet_products";

    public function request()
    {
        return $this->morphOne(Request::class, 'service');
    }

    public function postFeatures()
    {
        return $this->hasMany(PostFeature::class, 'post_id', 'id');
    }


    public function documents()
    {
        return $this->hasMany(Document::class, 'post_id', 'id');
    }

    public function providerDetails()
    {
        return $this->hasOne(Provider::class, 'id', 'provider');
    }
    public function getValidTillAttribute($value)
    {
        // Convert the valid_till string to a DateTime object
        $dateTime = new DateTime($value);
        
        // Format the DateTime object as desired
        return $dateTime->format('Y-m-d');
    }
}
