<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FAQ extends Model
{
    use HasFactory;
    
    protected $table="faqs";

    protected $guarded;


    public function categoryDetails(){
        return $this->belongsTo(Category::class,'category_id','id');
    }

}
