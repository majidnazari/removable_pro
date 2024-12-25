<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Eloquent;

/**
 * 
 *
 * @property-read \App\Models\User|null $Creator
 * @property-read \App\Models\User|null $Editor
 * @property-read \App\Models\Group|null $Group
 * @property-read \App\Models\Person|null $Person
 * @method static \Database\Factories\GroupDetailFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupDetail onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupDetail withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupDetail withoutTrashed()
 * @mixin Eloquent
 */
class GroupDetail extends Eloquent
{
    protected $fillable = [
        'creator_id',
        'editor_id',
        // 'person_id',
        'user_id',
        'group_id',
        'status',
    ];
    use HasFactory;
    use SoftDeletes;
    public const TABLE_NAME = 'group_details';
    protected $table = self::TABLE_NAME;

    public const COLUMN_ID = 'id';
    public const COLUMN_CREATOR_ID = 'creator_id';
    public const COLUMN_EDITOR_ID = 'editor_id';

    public const COLUMN_PERSON_ID = 'person_id';
    public const COLUMN_USER_ID = 'user_id';
    public const COLUMN_GROUP_ID = 'group_id';

    public function Group()
    {
        return $this->belongsTo(Group::class, self::COLUMN_GROUP_ID);
    }
    // public function Person()
    // {
    //     return $this->belongsTo(Person::class, self::COLUMN_PERSON_ID);
    // }
    public function UserCanSee()
    {
        return $this->belongsTo(User::class, self::COLUMN_USER_ID);
    }

    public function Creator()
    {
        return $this->belongsTo(User::class, self::COLUMN_CREATOR_ID);
    }

    public function Editor()
    {
        return $this->belongsTo(User::class, self::COLUMN_EDITOR_ID);
    }
    public function User()
    {
        return $this->belongsTo(User::class, self::COLUMN_USER_ID);
    }

    public static function getAuthorizationColumns()
    {
        return [
            "creator_id",
        ];
    }
}
