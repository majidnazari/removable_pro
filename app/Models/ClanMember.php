<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClanMember extends Model
{
    protected $fillable = ['creator_id', 'editor_id', 'Clan_id', 'family_code'];
    use HasFactory;

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
    public function editor()
    {
        return $this->belongsTo(User::class, 'editor_id');
    }

    
    public function Clan()
    {
        return $this->belongsTo(Clan::class, 'Clan_id');
    }
}
