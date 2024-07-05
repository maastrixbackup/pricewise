<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmartPhone extends Model
{
    use HasFactory;
    protected $fillable = ['provider_name', 'description', 'category_id', 'slug', 'status'];


    public function categoryDetails()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function featuresDetails()
    {
        return $this->belongsTo(ProviderFeature::class, 'id', 'provider_id');
    }

    public function discountsDetails()
    {
        return $this->belongsTo(ProviderDiscount::class, 'id', 'sp_provider');
    }
    
    public function faqDetails()
    {
        return $this->belongsTo(SmartPhoneFaq::class, 'id', 'provider_id');
    }
}
