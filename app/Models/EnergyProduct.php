<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTime;
use App\Models\Setting;
use Illuminate\Database\Eloquent\SoftDeletes;

class EnergyProduct extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "energy_products";
    protected $fillable = [
        'provider_id',
        'contract_length',
        'power_cost_per_unit',
        'gas_cost_per_unit',
        'tax_on_electric',
        'tax_on_gas',
        'ode_on_electric',
        'ode_on_gas',
        'power_origin',
        'type_of_current',
        'type_of_gas',
        'fixed_delivery',
        'grid_management',
        'feed_in_tariff',
        'vat',
        'energy_tax_reduction',
        'target_group',
        'discount',
        'status',
        'valid_till',
        'created_at',
        'updated_at'
    ];

    // public function request()
    // {
    //     return $this->morphOne(Request::class, 'service');
    // }

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
        return $this->hasOne(Provider::class, 'id', 'provider_id');
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
