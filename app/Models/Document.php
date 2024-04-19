<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = ['filename','path','post_id','type', 'category','sub_category'];
    
    public function categoryDetail()
    {
        return $this->belongsTo(Category::class, 'category');
    }
}
