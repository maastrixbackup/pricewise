<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    // protected $fillable = [
    //     'user_id',
    //     'key',
    //     'value',
    //     'user_type',        
    //     'status'
    // ];

   public function userDetails(){
    return $this->belongsTo(User::class, 'user_id', 'id');
   }
}
