<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostRequest extends Model
{
    use HasFactory;
    protected $fillable = ['request_id','key', 'value'];

    public function postRequest(){
        return $this->belongsTo(UserRequest::class, 'id', 'request_id');
    }
    
}
