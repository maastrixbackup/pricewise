<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GasMeasurementTariff extends Model
{
    use HasFactory;

    public function getGasSlabData()
    {
        return $this->belongsTo(EnergyGasConnectionSlab::class, 'slab_id', 'id');
    }
}
