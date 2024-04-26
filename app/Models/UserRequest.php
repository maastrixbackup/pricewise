<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRequest extends Model
{
    use HasFactory;

   public function service()
    {
        return $this->morphTo();
    }

   public function userDetails(){
    return $this->belongsTo(User::class, 'user_id', 'id');
   }
   public function providerDetails(){
    return $this->belongsTo(Provider::class, 'provider_id', 'id');
   }
   public function categoryDetails(){
    return $this->belongsTo(Category::class, 'category', 'id');
   }
   public function advantagesData(){
    return $this->hasMany(PostRequest::class, 'request_id', 'id');
   }
   public function feedInCost()
    {
        return $this->hasOne(FeedInCost::class, 'provider', 'provider_id');
    }
}
