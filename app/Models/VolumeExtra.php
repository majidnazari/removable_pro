<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VolumeExtra extends Model
{
    protected $fillable = ['title', 'day_number', 'description', 'status'];
    use HasFactory;
}
