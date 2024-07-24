<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoanProduct extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded;

    public function purposeDetails()
    {
        return $this->belongsTo(SpendingPurpose::class, 'p_id', 'id');
    }

    public function providerDetails()
    {
        return $this->hasMany(Bank::class, 'id', 'id');
    }
}
