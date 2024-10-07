<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class PersonSpouse extends Model
{
    protected $fillable = ['person_id', 'spouse_id', 'creator_id', 'editor_id', 'marrage_status', 'spouse_status', 'status', 'marrage_date', 'divorce_date'];
    use HasFactory,SoftDeletes;
}
