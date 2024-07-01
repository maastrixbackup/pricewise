<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmartPhone extends Model
{
    use HasFactory;
    protected $fillable = ['provider_name', 'description', 'slug', 'status'];
}
