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

}
