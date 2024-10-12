<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class FamilyBoard extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = ['creator_id', 'editor_id', 'category_content_id', 'title', 'selected_date', 'file_path', 'description', 'status'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'editor_id');
    }

    public function category()
    {
        return $this->belongsTo(CategoryContent::class, 'category_content_id');
    }
   

}
