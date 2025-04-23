<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnergyFeedInCharge extends Model
{
    use HasFactory;

    protected $table = 'energy_feed_in_charges';
    protected $fillable = [
        'range_from',
        'range_to',
        'cost_per_day',
        'cost_per_month',
        'created_at',
        'updated_at'
    ];
}
