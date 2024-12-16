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
 * @method static \Database\Factories\GroupCategoryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategory withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategory withoutTrashed()
 * @mixin Eloquent
 */
class GroupCategory extends Eloquent
{
    protected $fillable = [
        'creator_id',
        'editor_id',
        'title',
        'status',
    ];
    use HasFactory;
    use SoftDeletes;
    public const TABLE_NAME = 'group_categories';
    protected $table = self::TABLE_NAME;

    public const COLUMN_ID = 'id';
    public const COLUMN_CREATOR_ID = 'creator_id';
    public const COLUMN_EDITOR_ID = 'editor_id';
    public const COLUMN_GROUP_CATEGORY_ID = 'group_category_id';


    public function Creator()
    {
        return $this->belongsTo(User::class, self::COLUMN_CREATOR_ID);
    }

    public function Editor()
    {
        return $this->belongsTo(User::class, self::COLUMN_EDITOR_ID);
    }
    public function GroupCategoryDetails()
    {
        return $this->hasMany(GroupCategoryDetail::class,self::COLUMN_GROUP_CATEGORY_ID);
    }

    public static function getAuthorizationColumns()
    {
        return [
            "creator_id", 
        ];
    }
}
