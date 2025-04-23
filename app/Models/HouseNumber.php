<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HouseNumber extends Model
{
    use HasFactory;

    public function postalCodeDetails()
    {
        return $this->belongsTo(PostalCode::class,'pc_id', 'id');
    }
}
