<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class UserAnswer extends Model
{
    protected $fillable = ['creator_id', 'user_id', 'question_id', 'answer'];
    use HasFactory,SoftDeletes;

    public function Creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
    public function User()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function Question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}
