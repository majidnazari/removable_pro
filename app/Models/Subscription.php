<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Subscription extends Model
{
    protected $fillable = ['title', 'day_number', 'volume_amount', 'description', 'status'];
    use HasFactory,SoftDeletes;

}
