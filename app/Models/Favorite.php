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
 * @property int $creator_id
 * @property int|null $editor_id
 * @property int $person_id
 * @property int $group_category_id
 * @property string|null $image
 * @property string|null $title
 * @property string|null $description
 * @property string|null $star
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $Creator
 * @property-read \App\Models\User|null $Editor
 * @property-read \App\Models\Person $Person
 * @method static \Database\Factories\FavoriteFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite query()
 
 * @mixin \Eloquent
 */
class Favorite extends Eloquent
{
    protected $fillable = [
        'creator_id',
        'editor_id',
        'person_id',
        'group_category_id',
        'image',
        'title',
        'description',
        'star',
        'status',
    ];
    use HasFactory;
    use SoftDeletes;
    public const TABLE_NAME = 'favorites';
    protected $table = self::TABLE_NAME;

    public const COLUMN_ID = 'id';
    public const COLUMN_CREATOR_ID = 'creator_id';
    public const COLUMN_EDITOR_ID = 'editor_id';

    public const COLUMN_PERSON_ID = 'person_id';
    public const COLUMN_GROUP_CATEGORY_ID = 'group_category_id';

    public function Person()
    {
        return $this->belongsTo(Person::class, self::COLUMN_PERSON_ID);
    }
    public function GroupCategory()
    {
        return $this->belongsTo(GroupCategory::class, self::COLUMN_GROUP_CATEGORY_ID);
    }
    public function Creator()
    {
        return $this->belongsTo(User::class, self::COLUMN_CREATOR_ID);
    }

    public function Editor()
    {
        return $this->belongsTo(User::class, self::COLUMN_EDITOR_ID);
    }
    public function notifications()
    {
        return $this->morphMany(Notif::class, 'notifiable');
    }

    public static function getAuthorizationColumns()
    {
        return [
            "creator_id",
        ];
    }
}
