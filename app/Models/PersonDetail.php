<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class PersonDetail extends Model
{
    protected $fillable = ['person_id', 'profile_picture',  'physical_condition'];
    use HasFactory,SoftDeletes;

    public function Person()
    {
        return $this->belongsTo(Person::class, 'person_id');
    }
}
