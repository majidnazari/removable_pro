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
 * @method static \Database\Factories\ScoreFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Score newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Score newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Score onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Score query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Score whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Score whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Score whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Score whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Score whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Score whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Score withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Score withoutTrashed()
 * @mixin \Eloquent
 */
class Score extends  \Eloquent
{
    protected $fillable = [
        'title',
        'status',
    ];
    use HasFactory, SoftDeletes;

    public const TABLE_NAME = 'scores';
    public const COLUMN_ID = 'id';
    public const CREATOR_ID = 'creator_id';
    public const EDITOR_ID = 'editor_id';
  
    public const CATEGORY_CONTENT_ID = 'category_content_id';
    protected $table = self::TABLE_NAME;

}
