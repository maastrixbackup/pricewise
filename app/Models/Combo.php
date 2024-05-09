<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Combo extends Model
{
    use HasFactory;

    protected $guarded;

    public function categoryDetails(){
        return $this->belongsTo(Category::class,'category','id');
    }
}
