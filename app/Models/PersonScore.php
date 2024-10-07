<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class PersonScore extends Model
{
    protected $fillable = ['creator_id', 'editor_id', 'person_id', 'score_id', 'score_level', 'status'];
    use HasFactory,SoftDeletes;
}
