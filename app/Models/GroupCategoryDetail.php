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
 * @property string|null $image
 * @property string|null $title
 * @property string|null $description
 * @property int $star 0=none  1=1 2=2 3=3 4=4 5=5
 * @property int $status 1=Active 2=Inactive
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $Creator
 * @property-read \App\Models\User|null $Editor
 * @property-read \App\Models\Group|null $Group
 * @property-read GroupCategoryDetail|null $GroupCategoryDetail
 * @method static \Database\Factories\GroupCategoryDetailFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategoryDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategoryDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategoryDetail onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategoryDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategoryDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategoryDetail whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategoryDetail whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategoryDetail whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategoryDetail whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategoryDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategoryDetail whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategoryDetail wherePersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategoryDetail whereStar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategoryDetail whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategoryDetail whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategoryDetail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategoryDetail withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GroupCategoryDetail withoutTrashed()
 * @mixin Eloquent
 */
class GroupCategoryDetail extends Eloquent
{
    protected $fillable = [
        'creator_id',
        'editor_id',
        'group_id',
        'group_category_id',
        'status',
    ];
    use HasFactory;
    use SoftDeletes;
    public const TABLE_NAME = 'group_categories';
    protected $table = self::TABLE_NAME;

    public const COLUMN_ID = 'id';
    public const COLUMN_CREATOR_ID = 'creator_id';
    public const COLUMN_EDITOR_ID = 'editor_id';

    public const COLUMN_GROUP_ID = 'group_id';
    public const COLUMN_GROUP_CATEGORY_ID = 'group_category_id';

    public function Group()
    {
        return $this->belongsTo(Group::class, self::COLUMN_GROUP_ID);
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

     // Method to get all persons in related groups using Eloquent
     public function PersonsInRelatedGroups()
     {
         // Eager load the related groups and their associated persons
         return $this->groups()
             ->with('GroupDetails.Person')
             ->get()
             ->flatMap(function ($group) {
                 // Flatten all persons from related groups into one collection
                 return $group->GroupDetails->pluck('person');
             });
     }

    public static function getAuthorizationColumns()
    {
        return [
            "creator_id",
        ];
    }
}
