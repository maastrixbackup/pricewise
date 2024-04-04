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

    public function postFeatures()
    {
        return $this->hasMany(PostFeature::class, 'post_id', 'id');
    }

    public function subCategory()
    {
        return $this->belongsTo(Category::class, 'sub_category');
    }

    public function categoryDetail()
    {
        return $this->belongsTo(Category::class, 'category');
    }
    public function getValidTillAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }
}
