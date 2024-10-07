<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class FamilyBoard extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [ 'category_id', 'creator_id', 'editor_id', 'title', 'descriptions','status'];

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
