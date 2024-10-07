<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Clan extends Model
{
    protected $fillable = ['creator_id', 'editor_id', 'title', 'Clan_exact_family_name', 'Clan_code'];
    use HasFactory, SoftDeletes;



    public function person()
    {
        return $this->belongsTo(Person::class, 'creator_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'editor_id');
    }
    public function members()
    {
        return $this->hasMany(ClanMember::class, 'Clan_id');
    }
}
