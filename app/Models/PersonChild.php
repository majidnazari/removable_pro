<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class PersonChild extends Model
{
    protected $fillable = ['creator_id', 'editor_id', 'person_spouse_id', 'child_id', 'child_kind', 'child_status', 'status'];
    use HasFactory,SoftDeletes;

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
    public function editor()
    {
        return $this->belongsTo(User::class, 'editor_id');
    }

    public function marrage()
    {
        return $this->belongsTo(PersonSpouse::class, 'person_spouse_id');
    }
}
