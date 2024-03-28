<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostFeature extends Model
{
    use HasFactory;
    protected $fillable = ['post_id','post_category', 'category_id','feature_id', 'feature_value'];

    
}
