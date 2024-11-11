<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * 
 *
 * @property int $id
 * @property string $title
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Database\Factories\GroupViewFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupView newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupView newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupView onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupView query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupView whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupView whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupView whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupView whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupView whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupView whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupView withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupView withoutTrashed()
 * @mixin \Eloquent
 */
class GroupView extends  \Eloquent
{
    protected $fillable = [
        'title',
        'status',
    ];
    use HasFactory, SoftDeletes;

    public const TABLE_NAME = 'group_views';
    public const COLUMN_ID = 'id';
    public const CREATOR_ID = 'creator_id';
    public const EDITOR_ID = 'editor_id';
  
    public const CATEGORY_CONTENT_ID = 'category_content_id';
    protected $table = self::TABLE_NAME;
}
