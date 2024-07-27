<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CyberSecurity extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function featureDetails()
    {
        return $this->belongsTo(SecurityFeature::class, 'features', 'id');
    }

    public function providerDetails()
    {
        return $this->belongsTo(SecurityProvider::class, 'provider_id', 'id');
    }
}
