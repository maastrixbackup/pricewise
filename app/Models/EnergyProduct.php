<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTime;
use App\Models\Setting;

class EnergyProduct extends Model
{
    use HasFactory;
    protected $table = "energy_products";

    public function request()
    {
        return $this->morphOne(Request::class, 'service');
    }

    public function postFeatures()
    {
        return $this->hasMany(PostFeature::class, 'post_id', 'id');
    }
    public function prices()
    {
        return $this->hasOne(EnergyRateChat::class, 'provider', 'provider');
    }
    public function feedInCost()
    {
        return $this->hasOne(FeedInCost::class, 'provider', 'provider');
    }
    public function govtTaxes()
    {
        return $this->belongsTo(Setting::class)->where('type', 'business_general')->whereIn('sub_type', ['gas', 'current']);
    }
    public function providerDetails()
    {
        return $this->hasOne(Provider::class, 'id', 'provider');
    }
    public function documents()
    {
        return $this->hasMany(Document::class, 'post_id', 'id');
    }
    public function getValidTillAttribute($value)
    {
        // Convert the valid_till string to a DateTime object
        $dateTime = new DateTime($value);
        
        // Format the DateTime object as desired
        return $dateTime->format('Y-m-d');
    }
}
