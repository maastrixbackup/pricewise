<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmartPhoneFaq extends Model
{
    use HasFactory;
    protected $fillable = ['provider_id', 'title', 'slug', 'description', 'status'];


    public function providerDetails()
    {
        return $this->belongsTo(SmartPhone::class, 'provider_id', 'id');
    }
}
