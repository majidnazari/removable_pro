<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class UserRelation extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Define the table name (if it differs from default 'user_relations')
    protected $table = 'user_relations';

    // Specify the columns that are mass assignable
    protected $fillable = [
        'creator_id',
        'editor_id',
        'related_with_user_id',
    ];

    // Define the relationships

    /**
     * The user who created the relation.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * The user who edited the relation.
     */
    public function editor()
    {
        return $this->belongsTo(User::class, 'editor_id');
    }

    /**
     * The related user this relation is associated with.
     */
    public function relatedUser()
    {
        return $this->belongsTo(User::class, 'related_with_user_id');
    }
}
