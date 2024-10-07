<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class FamilyEvent extends Model
{
    protected $fillable = ['person_id', 'event_id', 'creator_id', 'editor_id', 'event_date', 'status'];
    use HasFactory,SoftDeletes;
}
