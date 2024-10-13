<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Area extends Model
{
    protected $fillable = ['city_id', 'title', 'code'];
    use HasFactory,SoftDeletes;

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

}
