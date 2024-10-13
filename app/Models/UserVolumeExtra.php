<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class UserVolumeExtra extends Model
{
    protected $fillable = ['user_id', 'volume_extra_id', 'remain_volume', 'start_date', 'end_date', 'status'];
    use HasFactory,SoftDeletes;


    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
}
