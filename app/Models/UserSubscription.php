<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class UserSubscription extends Model
{
    protected $fillable = ['user_id', 'subscription_id', 'start_date', 'end_date', 'remain_volume', 'status'];
    use HasFactory,SoftDeletes;
}
