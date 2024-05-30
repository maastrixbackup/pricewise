<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class insuranceProductCoverage extends Model
{
    use HasFactory;


    public function coverages()
    {
        return $this->hasMany(insuranceProductCoverage::class, 'insurance_product_id', 'id');
    }
    public function coverageDetails()
    {
        return $this->belongsTo(insuranceCoverage::class, 'insurance_coverage_id', 'id');
    }
}
