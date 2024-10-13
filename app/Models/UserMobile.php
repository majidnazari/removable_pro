<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class UserMobile extends Model
{
    protected $fillable = ['creator_id', 'user_id', 'mobile'];
    use HasFactory,SoftDeletes;

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
