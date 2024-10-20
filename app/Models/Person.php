<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Person extends Model
{
    protected $fillable = ['creator_id', 'editor_id', 'node_code', 'node_level_x', 'node_level_y', 'first_name', 'last_name', 'birth_date', 'death_date', 'is_owner', 'status'];
    use HasFactory , SoftDeletes;


    public function Creator()
    {
        return $this->belongsTo(User::class,'creator_id');
    }
    public function Editor()
    {
        return $this->belongsTo(User::class,'editor_id');
    }


    public function PersonSpouses()
    {
        // return $this->hasMany(PersonSpouse::class, 'person_id')->orwhere('spouse_id',$this->id);
         return $this->hasMany(PersonSpouse::class, 'person_id');

    }
  
    public function Addresses()
    {
        return $this->hasMany(Address::class, 'address_id');
    }

    public function Memories()
    {
        return $this->hasMany(Memory::class, 'person_id');
    }
    public function FamilyEvents()
    {
        return $this->hasMany(FamilyEvent::class, 'person_id');
    }
    public function Favorites()
    {
        return $this->hasMany(Favorite::class, 'person_id');
    }

    public function Scores()
    {
        return $this->hasMany(Score::class, 'score_id');
    }


    // public function Children()
    // {
    //     return $this->hasMany(Person::class, 'id');
    // }
    // public function Children()
    // {
    //     return $this->hasManyThrough(
    //         Person::class,              // Final model we want to access (child Person)
    //         PersonChild::class,         // Intermediate model (PersonChild)
    //         'person_spouse_id',         // Foreign key on PersonChild table
    //         'id',                       // Foreign key on Person table (child's ID)
    //         'id',                       // Local key on PersonSpouse table (parent's ID)
    //         'child_id'                  // Local key on PersonChild table (child's ID)
    //     )->whereHas('PersonSpouses', function($query) {
    //         $query->where('person_id', $this->id)
    //               ->orWhere('spouse_id', $this->id);
    //     })->whereHas('PersonChild', function($query) {
    //         $query->where('person_spouse_id', $this->id);
                 
    //     });
    // }

    public function Children()
    {
        return $this->hasManyThrough(
            Person::class,             // Final model to access (the child Person)
            PersonChild::class,        // Intermediate model (PersonChild)
            'person_spouse_id',        // Foreign key on PersonChild table
            'id',                       // Foreign key on Person table (child's ID)
            'id',                       // Local key on PersonSpouse table (parent's ID)
            'child_id'                 // Local key on PersonChild table (child's ID)
        );
    }
}

  