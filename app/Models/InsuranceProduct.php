<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class InsuranceProduct extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime:d-m-Y',
        'updated_at' => 'datetime:d-m-Y',
        'valid_till' => 'date:d-m-Y',
    ];

    public function InsuranceProduct()
    {
        return $this->morphOne(Request::class, 'service');
    }

    public function postFeatures()
    {
        return $this->hasMany(PostFeature::class, 'post_id', 'id');
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category', 'id');
    }

    public function categoryDetail()
    {
        return $this->belongsTo(Category::class, 'category');
    }
    public function getValidTillAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }
    public function providerDetails()
    {
        return $this->belongsTo(Provider::class, 'category', 'category');
    }
    public function coverages()
    {
        return $this->hasMany(insuranceProductCoverage::class, 'insurance_product_id', 'id');
    }
}
