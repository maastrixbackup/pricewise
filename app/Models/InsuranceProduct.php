<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsuranceProduct extends Model
{
    use HasFactory;

    public function postFeatures()
    {
        return $this->hasMany(PostFeature::class, 'post_id', 'id');
    }
}
