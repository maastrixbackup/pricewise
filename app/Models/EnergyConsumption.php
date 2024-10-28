<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnergyConsumption extends Model
{
    use HasFactory;
    protected $table = "energy_consumptions";
    protected $fillable = ['no_of_person', 'gas_supply', 'electric_supply', 'house_type', 'cat_id', 'created_at', 'updated_at'];
}
