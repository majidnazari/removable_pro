<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class PersonScore extends Model
{
    protected $fillable = ['creator_id', 'editor_id', 'person_id', 'score_id', 'score_level', 'status'];
    use HasFactory,SoftDeletes;

    public function Person()
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

    public function Creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function Editor()
    {
        return $this->belongsTo(User::class, 'editor_id');
    }
    public function Score()
    {
        return $this->belongsTo(Score::class, 'score_id');
    }
}
