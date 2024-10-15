<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class CategoryContent extends Model
{
    use HasFactory , SoftDeletes;
    protected $fillable = ['title', 'status'];

    public function FamilyBoards()
    {
        return $this->hasMany(FamilyBoard::class, 'category_content_id');
    }
    public function Memories()
    {
        return $this->hasMany(Memory::class, 'category_content_id');
    }
}
