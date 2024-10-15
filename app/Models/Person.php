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

    public function Children()
    {
        return $this->hasMany(PersonChild::class, 'person_id');
    }
    public function Spouses()
    {
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
    
}

  