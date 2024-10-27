<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class PersonMarriage extends Model
{
    protected $fillable = ['man_id', 'woman_id', 'creator_id', 'editor_id', 'marriage_status', 'status', 'marriage_date', 'divorce_date'];
    use HasFactory,SoftDeletes;

    public function Creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function Editor()
    {
        return $this->belongsTo(User::class, 'editor_id');
    }

    public function Man()
    {
        return $this->belongsTo(Person::class, 'man_id');
    }

    public function Woman()
    {
        return $this->belongsTo(Person::class, 'woman_id');
    }

    public function PersonChild()
    {
        return $this->hasMany(PersonChild::class, 'person_marriage_id');
    }
    
    public function Children()
    {
        return $this->hasManyThrough(
            Person::class,
            PersonChild::class,
            'person_marriage_id', // Foreign key on the `PersonChild` table
            'id',               // Foreign key on the `Person` table
            'id',               // Local key on the `PersonMarriage` table
            'child_id'          // Local key on the `PersonChild` table
        );
    }

    
}
