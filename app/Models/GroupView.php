<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Eloquent;


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

 * @mixin \Eloquent
 */
class GroupView extends Eloquent
{
    protected $fillable = [
        'title',
        'status',
    ];
    use HasFactory;
    use SoftDeletes;

    public const TABLE_NAME = 'group_views';
    protected $table = self::TABLE_NAME;

    public const COLUMN_ID = 'id';
    public const COLUMN_CREATOR_ID = 'creator_id';
    public const COLUMN_EDITOR_ID = 'editor_id';

    public const COLUMN_CATEGORY_CONTENT_ID = 'category_content_id';
}
