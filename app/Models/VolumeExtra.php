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
 * @property int $day_number
 * @property string|null $description
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Database\Factories\VolumeExtraFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VolumeExtra newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VolumeExtra newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VolumeExtra onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VolumeExtra query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VolumeExtra whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VolumeExtra whereDayNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VolumeExtra whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VolumeExtra whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VolumeExtra whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VolumeExtra whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VolumeExtra whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VolumeExtra whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VolumeExtra withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VolumeExtra withoutTrashed()
 * @mixin \Eloquent
 */
class VolumeExtra extends  \Eloquent
{
    protected $fillable = [
        'title',
        'day_number',
        'description',
        'status',
    ];
    use HasFactory, SoftDeletes;

    public const TABLE_NAME = 'volume_extras';
    public const COLUMN_ID = 'id';
    public const CREATOR_ID = 'creator_id';
    public const EDITOR_ID = 'editor_id';
  
    public const CATEGORY_CONTENT_ID = 'category_content_id';
    protected $table = self::TABLE_NAME;
}
