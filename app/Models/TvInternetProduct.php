<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TvInternetProduct extends Model
{
    use HasFactory;
    protected $table = "tv_internet_products";

    public function postFeatures()
    {
        return $this->hasMany(PostFeature::class, 'post_id', 'id');
    }


    public function documents()
    {
        return $this->hasMany(Document::class, 'post_id', 'id');
    }
}
