<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reimbursement extends Model
{
    use HasFactory;
    protected $table = "reimbursements";

    public function parentCat()
    {
        return $this->belongsTo(Reimbursement::class, 'parent');
    }
}
