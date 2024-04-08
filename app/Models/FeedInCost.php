<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedInCost extends Model
{
    use HasFactory;
    //protected $table = "reimbursements";

    public function providerDetail()
    {
        return $this->belongsTo(Provider::class, 'provider');
    }
}
