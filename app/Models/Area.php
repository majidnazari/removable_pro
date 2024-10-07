<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $fillable = ['city_id', 'title', 'code'];
    use HasFactory;

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

}
