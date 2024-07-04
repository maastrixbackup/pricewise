<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderDiscount extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'slug', 'sp_provider', 'discount_type', 'discount', 'status', 'valid_form', 'valid_till'];

    public function providerDetails()
    {
        return $this->belongsTo(SmartPhone::class, 'sp_provider', 'id');
    }
}
