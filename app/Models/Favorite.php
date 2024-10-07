<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Favorite extends Model
{
    protected $fillable = ['creator_id', 'editor_id', 'person_id', 'image', 'title', 'description', 'star', 'status'];
    use HasFactory,SoftDeletes;
}
