<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;
    protected $table = "features";

    public function categoryDetail(){
        return $this->hasOne(Category::class, 'id', 'category'); 
    }
}
