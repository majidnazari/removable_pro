<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ClanMember extends Model
{
    protected $fillable = ['creator_id', 'editor_id', 'clan_id','related_to','biggest_person_id', 'node_code'];
    use HasFactory,SoftDeletes;

    public function Creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
    public function Editor()
    {
        return $this->belongsTo(User::class, 'editor_id');
    }

    
    public function FamilyRelations()
    {
        return $this->belongsTo(Clan::class, 'related_to');
    }
    public function Person()
    {
        return $this->belongsTo(Person::class, 'biggest_person_id');
    }
}
