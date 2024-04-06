<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnergyRateChat extends Model
{
    use HasFactory;
    //protected $table = "reimbursements";

    public function providerDetail()
    {
        return $this->belongsTo(Provider::class, 'provider');
    }
}
