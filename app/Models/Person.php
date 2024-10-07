<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Person extends Model
{
    protected $fillable = ['creator_id', 'editor_id', 'node_code', 'node_level_x', 'node_level_y', 'first_name', 'last_name', 'birth_date', 'death_date', 'is_owner', 'status'];
    use HasFactory , SoftDeletes;


    public function creator()
    {
        return $this->belongsTo(User::class,'creator_id');
    }
    public function editor()
    {
        return $this->belongsTo(User::class,'editor_id');
    }

    public function children()
    {
        return $this->hasMany(PersonChild::class, 'person_id');
    }
    public function spouses()
    {
        return $this->hasMany(PersonSpouse::class, 'person_id');
    }

    public function addresses()
    {
        return $this->hasMany(Address::class, 'address_id');
    }

    public function memories()
    {
        return $this->hasMany(Memory::class, 'person_id');
    }
    public function family_events()
    {
        return $this->hasMany(FamilyEvent::class, 'person_id');
    }
    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'person_id');
    }

    public function scores()
    {
        return $this->hasMany(Score::class, 'score_id');
    }
    
}

  