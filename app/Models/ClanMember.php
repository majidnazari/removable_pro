<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ClanMember extends Model
{
    protected $fillable = ['creator_id', 'editor_id', 'clan_id', 'node_code'];
    use HasFactory,SoftDeletes;

    public function Creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
    public function Editor()
    {
        return $this->belongsTo(User::class, 'editor_id');
    }

    
    public function Clan()
    {
        return $this->belongsTo(Clan::class, 'clan_id');
    }
}
