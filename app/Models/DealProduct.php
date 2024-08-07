<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DealProduct extends Model
{
    use HasFactory;
    use SoftDeletes;
    // protected $guarded;

    public function categoryDetails()
    {
        return $this->belongsTo(ProductCategory::class, 'category', 'id'); // Adjust the keys as necessary
    }

}
