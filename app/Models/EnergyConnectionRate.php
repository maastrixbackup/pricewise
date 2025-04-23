<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnergyConnectionRate extends Model
{
    use HasFactory;

    public function getCurrentSlabData()
    {
        return $this->belongsTo(EnergyElectricConnectionSlab::class, 'slab_id', 'id');
    }

    public function getGasSlabData()
    {
        return $this->belongsTo(EnergyGasConnectionSlab::class, 'slab_id', 'id');
    }
}
