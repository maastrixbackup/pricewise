<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderFaq extends Model
{
    use HasFactory;


    protected $table = "provider_faqs";
    protected $fillable = [
        'provider_id',
        'title',
        'description',
        'created_at',
        'updated_at'
    ];
}
