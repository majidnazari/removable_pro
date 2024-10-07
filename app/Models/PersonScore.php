<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class PersonScore extends Model
{
    protected $fillable = ['creator_id', 'editor_id', 'person_id', 'score_id', 'score_level', 'status'];
    use HasFactory,SoftDeletes;

    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'editor_id');
    }
    public function score()
    {
        return $this->belongsTo(Score::class, 'score_id');
    }
}
