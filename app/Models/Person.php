<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Person extends Model
{
    protected $fillable = ['creator_id', 'editor_id', 'node_code', 'node_level_x', 'node_level_y', 'first_name', 'last_name', 'birth_date', 'death_date', 'is_owner', 'status'];
    use HasFactory , SoftDeletes;

}

  