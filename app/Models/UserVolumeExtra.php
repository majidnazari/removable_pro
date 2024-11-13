<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property int $volume_extra_id
 * @property int|null $remain_volume (MB)
 * @property string|null $start_date
 * @property string|null $end_date
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $Creator
 * @method static \Database\Factories\UserVolumeExtraFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserVolumeExtra newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserVolumeExtra newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserVolumeExtra onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserVolumeExtra query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserVolumeExtra whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserVolumeExtra whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserVolumeExtra whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserVolumeExtra whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserVolumeExtra whereRemainVolume($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserVolumeExtra whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserVolumeExtra whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserVolumeExtra whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserVolumeExtra whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserVolumeExtra whereVolumeExtraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserVolumeExtra withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserVolumeExtra withoutTrashed()
 * @mixin \Eloquent
 */
class UserVolumeExtra extends \Eloquent
{
    protected $fillable = [
        'user_id',
        'volume_extra_id',
        'remain_volume',
        'start_date',
        'end_date',
        'status',
    ];
    use HasFactory, SoftDeletes;

    public const TABLE_NAME = 'user_volume_extras';
    public const COLUMN_ID = 'id';
    public const CREATOR_ID = 'creator_id';
    public const EDITOR_ID = 'editor_id';
    public const USER_ID = 'user_id';


    public const CATEGORY_CONTENT_ID = 'category_content_id';
    protected $table = self::TABLE_NAME;


    public function Creator()
    {
        return $this->belongsTo(User::class, self::USER_ID);
    }

}
