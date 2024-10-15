<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Province extends Model
{
    protected $fillable = ['country_id', 'title', 'code'];
    use HasFactory,SoftDeletes;

    public function Country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
   
}

   
