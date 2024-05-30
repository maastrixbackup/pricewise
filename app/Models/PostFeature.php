<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostFeature extends Model
{
    use HasFactory;
    protected $fillable = ['post_id','post_category', 'category_id', 'sub_category', 'feature_id', 'feature_value', 'details'];

    public function postCategory(){
        return $this->hasOne(Category::class, 'id', 'post_category');
    }

    public function postFeature(){
        return $this->hasOne(Feature::class, 'id', 'feature_id');
    }
}
