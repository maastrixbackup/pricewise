<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{

    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'event_type',
        'caterer_id',
        'description',
        'location',
        'postal_code',
        'house_no',
        'room_type',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'image',
        'catering_price',
        'decoration_price',
        'photoshop_price',
        'status',
        'state_id'
    ];

    public function roomDetails()
    {
        return $this->belongsTo(EventRoom::class, 'room_type', 'id');
    }

    public function catererDetails()
    {
        return $this->belongsTo(Caterer::class, 'caterer_id', 'id');
    }

    public function EventTypes()
    {
        return $this->belongsTo(EventType::class, 'event_type', 'id');
    }

    // public function themeDetails()
    // {
    //     return $this->belongsTo(EventTheme::class, 'theme_type', 'id');
    // }
}
