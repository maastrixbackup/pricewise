<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlobalEnergySetting extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'tax_on_electric', 'tax_on_gas', 'ode_on_electric', 'ode_on_gas', 'energy_tax_reduction', 'vat'];
}
