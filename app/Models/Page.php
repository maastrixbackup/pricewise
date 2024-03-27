<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Page extends Model
{
    use HasFactory;
    protected $fillable = ['title','description','image', 'slug', 'status'];

    public function setTitleAttribute($value)
	{
    $this->attributes['title'] = $value;
    $this->attributes['slug'] = Str::slug($value);
	}

	public function getRouteKeyName()
    {
        return 'slug';
    }
}
