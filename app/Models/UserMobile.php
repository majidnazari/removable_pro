<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMobile extends Model
{
    protected $fillable = ['creator_id', 'user_id', 'mobile'];
    use HasFactory;

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
