<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;
    protected $table = "features";

    public function subCategory()
    {
        return $this->belongsTo(Category::class, 'sub_category');
    }

    public function categoryDetail()
    {
        return $this->belongsTo(Category::class, 'category');
    }
}
