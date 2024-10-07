<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class PersonChild extends Model
{
    protected $fillable = ['creator_id', 'editor_id', 'person_spouse_id', 'child_id', 'child_kind', 'child_status', 'status'];
    use HasFactory,SoftDeletes;
}
