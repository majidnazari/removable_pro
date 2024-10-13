<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class UserAnswer extends Model
{
    protected $fillable = ['creator_id', 'user_id', 'question_id', 'answer'];
    use HasFactory,SoftDeletes;

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}
