<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class PersonSpouse extends Model
{
    protected $fillable = ['person_id', 'spouse_id', 'creator_id', 'editor_id', 'marrage_status', 'spouse_status', 'status', 'marrage_date', 'divorce_date'];
    use HasFactory,SoftDeletes;

    public function Creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function Editor()
    {
        return $this->belongsTo(User::class, 'editor_id');
    }

    public function Itself()
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

    public function Spouse()
    {
        return $this->belongsTo(Person::class, 'spouse_id');
    }
}
