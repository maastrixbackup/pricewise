<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostReimbursement extends Model
{
    use HasFactory;
    protected $fillable = ['post_id','reimburse_id', 'reimburse_value'];

    
}
