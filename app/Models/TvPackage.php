<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TvPackage extends Model
{
    use HasFactory;

    protected $guarded;


    public function providerDetails(){
        return $this->belongsTo(Provider::class, 'provider_id'); 
    }
}
