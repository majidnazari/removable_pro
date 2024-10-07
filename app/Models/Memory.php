<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Memory extends Model
{
    protected $fillable = ['person_id', 'category_content_id', 'group_view_id', 'creator_id', 'editor_id', 'content', 'title', 'description', 'is_shown_after_death', 'status'];
    use HasFactory,SoftDeletes;

    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id');
    }
    public function category()
    {
        return $this->belongsTo(CategoryContent::class, 'category_content_id');
    }
    public function group_view()
    {
        return $this->belongsTo(GroupView::class, 'group_view_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'editor_id');
    }
}
