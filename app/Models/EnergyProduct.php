<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnergyProduct extends Model
{
    use HasFactory;
    protected $table = "energy_products";

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
    public function providerDetails()
    {
        return $this->hasOne(Provider::class, 'id', 'provider');
    }
    public function documents()
    {
        return $this->hasMany(Document::class, 'post_id', 'id');
    }
}
