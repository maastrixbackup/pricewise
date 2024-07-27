<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SecurityProvider extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function countryDetails()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }
}
