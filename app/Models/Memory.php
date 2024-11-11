<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * 
 *
 * @property int $id
 * @property int $person_id
 * @property int $category_content_id
 * @property int $group_view_id
 * @property int $creator_id
 * @property int|null $editor_id
 * @property string|null $content
 * @property string $title
 * @property string|null $description
 * @property int $is_shown_after_death
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\CategoryContent $Category
 * @property-read \App\Models\User $Creator
 * @property-read \App\Models\User|null $Editor
 * @property-read \App\Models\GroupView $GroupView
 * @property-read \App\Models\Person $Person
 * @method static \Database\Factories\MemoryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory whereCategoryContentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory whereGroupViewId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory whereIsShownAfterDeath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory wherePersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Memory withoutTrashed()
 * @mixin \Eloquent
 */
class Memory extends  \Eloquent
{
    protected $fillable = [
        'person_id',
        'category_content_id',
        'group_view_id',
        'creator_id',
        'editor_id',
        'content',
        'title',
        'description',
        'is_shown_after_death',
        'status',
    ];
    use HasFactory, SoftDeletes;
    public const TABLE_NAME = 'memories';
    public const COLUMN_ID = 'id';
    public const CREATOR_ID = 'creator_id';
    public const EDITOR_ID = 'editor_id';
  
    public const CATEGORY_CONTENT_ID = 'category_content_id';
    public const PERSON_ID = 'person_id';
    public const GROUP_VIEW_ID = 'group_view_id';
    protected $table = self::TABLE_NAME;

    public function Person()
    {
        return $this->belongsTo(Person::class, SELF::PERSON_ID);
    }
    public function Category()
    {
        return $this->belongsTo(CategoryContent::class, SELF::CATEGORY_CONTENT_ID);
    }
    public function GroupView()
    {
        return $this->belongsTo(GroupView::class, SELF::GROUP_VIEW_ID);
    }

    public function Creator()
    {
        return $this->belongsTo(User::class, SELF::CREATOR_ID);
    }

    public function Editor()
    {
        return $this->belongsTo(User::class, SELF::EDITOR_ID);
    }
}
