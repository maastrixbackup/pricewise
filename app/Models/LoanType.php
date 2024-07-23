<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoanType extends Model
{
    use SoftDeletes;
    use HasFactory;


    protected $guarded;

    // Optionally, specify the date format for the deleted_at column
    protected $dates = ['deleted_at'];

    public function purposeDetails()
    {
        return $this->belongsTo(SpendingPurpose::class, 'p_id', 'id');
    }
}
