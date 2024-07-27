<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;
    protected $fillable = ['category_id', 'title', 'slug', 'image', 'status'];

    public function categoryDetails()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
