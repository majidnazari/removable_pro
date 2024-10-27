<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class PersonChild extends Model
{
    protected $fillable = ['creator_id', 'editor_id', 'person_marriage_id', 'child_id', 'child_kind', 'child_status', 'status'];
    use HasFactory,SoftDeletes;

    public function Creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
    public function Editor()
    {
        return $this->belongsTo(User::class, 'editor_id');
    }

    public function PersonMarriage()
    {
        return $this->belongsTo(PersonMarriage::class, 'person_marriage_id');
    }
    public function WhoseChild()
    {
        return $this->belongsTo(Person::class, 'child_id');
    }

}
