<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Memory extends Model
{
    protected $fillable = ['person_id', 'category_content_id', 'group_view_id', 'creator_id', 'editor_id', 'content', 'title', 'description', 'is_shown_after_death', 'status'];
    use HasFactory,SoftDeletes;
}
