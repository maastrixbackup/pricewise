<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderFeature extends Model
{
    use HasFactory;
    protected $fillable = ['provider_id', 'mobile_data', 'call_text', 'price', 'valid_till', 'description', 'status'];

    public function providerDetails()
    {
        return $this->belongsTo(SmartPhone::class, 'provider_id', 'id');
    }
}
