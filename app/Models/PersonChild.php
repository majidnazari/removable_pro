<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class PersonChild extends Model
{
    protected $fillable = ['creator_id', 'editor_id', 'person_spouse_id', 'child_id', 'child_kind', 'child_status', 'status'];
    use HasFactory,SoftDeletes;

    public function Creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
    public function Editor()
    {
        return $this->belongsTo(User::class, 'editor_id');
    }

    public function PersonSpouse()
    {
        return $this->belongsTo(PersonSpouse::class, 'person_spouse_id');
    }
    public function WhoseChild()
    {
        return $this->belongsTo(Person::class, 'child_id');
    }

}
