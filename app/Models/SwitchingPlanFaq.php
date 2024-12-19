<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SwitchingPlanFaq extends Model
{
    use HasFactory;

    protected $table = "switching_plan_faqs";
    protected $fillable = [
        'provider_id',
        'question',
        'answer',
        'title',
        'description',
        'created_at',
        'updated_at'
    ];
}
