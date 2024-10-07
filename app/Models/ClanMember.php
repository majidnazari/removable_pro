<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClanMember extends Model
{
    protected $fillable = ['creator_id', 'editor_id', 'clan_id', 'family_code'];
    use HasFactory;
}
