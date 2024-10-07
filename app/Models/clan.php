<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class clan extends Model
{
    protected $fillable = ['creator_id', 'editor_id', 'title', 'clan_exact_family_name', 'clan_code'];
    use HasFactory,SoftDeletes;

    
}
