<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'post_id',
        'category',
        'user_type',        
        'status',
        'sub_category',
        'rating',
        'rating_type',
        'description'
    ];

   
}
