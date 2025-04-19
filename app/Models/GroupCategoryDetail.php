<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Log;
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
 * @property int $star 0=none  1=One 2=Two 3=Three 4=Four 5=Five
 * @property int $status 1=Active 2=Inactive
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $Creator
 * @property-read \App\Models\User|null $Editor
 * @property-read \App\Models\Group|null $Group
 * @property-read GroupCategoryDetail|null $GroupCategoryDetail
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
    public const TABLE_NAME = 'group_category_details';
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
    public function Groups()
    {
        return $this->hasMany(Group::class, self::COLUMN_ID);
    }

    public function GroupCategory()
    {
        return $this->belongsTo(GroupCategory::class, self::COLUMN_GROUP_CATEGORY_ID);  // Corrected to use group_category_id
    }

    public function GroupDetails()
    {
        return $this->hasMany(GroupDetail::class, self::COLUMN_GROUP_ID);
    }
    public function Creator()
    {
        return $this->belongsTo(User::class, self::COLUMN_CREATOR_ID);
    }

    public function Editor()
    {
        return $this->belongsTo(User::class, self::COLUMN_EDITOR_ID);
    }

    // public function getPersonsInRelatedGroups()
    // {

    // //    $groupCategoryDetail = GroupCategoryDetail::find(4);
    // //     $group = $groupCategoryDetail->Group;
    //     // Fetch all persons through the Group -> GroupDetails -> Person relationship
    //     $persons = $this->Group()
    //         ->with('GroupDetails.Person')
    //         ->get()
    //         ->flatMap(function ($group) {
    //             return $group->GroupDetails->pluck('Person');
    //         });

    //       Log::info("The persons are: " . json_encode( $persons));

    //     return $persons;
    // }

    public function personsInRelatedGroups()
    {
        return $this->hasManyThrough(Person::class, GroupDetail::class, 'group_id', 'id', 'group_id', 'person_id');
    }

    public static function getAuthorizationColumns()
    {
        return [
            "creator_id",
        ];
    }
}
