<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventTheme extends Model
{
    use HasFactory;
    protected $fillable = ['theme_type', 'description', 'image', 'status'];

    public function countryDetails()
    {
        return $this->belongsTo(Country::class, 'theme_type', 'id');
    }
}
