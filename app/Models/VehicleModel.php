<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleModel extends Model
{
    use HasFactory;

    protected $table = "models";

    public function brandDetails(){
        return $this->belongsTo(Brand::class,'brand_id','id');
    }
}
