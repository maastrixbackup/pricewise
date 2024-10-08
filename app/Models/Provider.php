<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Provider extends Model
{
    use HasFactory;

    public function categoryDetail(){
        return $this->hasOne(Category::class, 'id', 'category');
    }
}
