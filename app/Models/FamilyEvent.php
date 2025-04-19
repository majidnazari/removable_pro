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
 * @property int $person_id
 * @property int $event_id
 * @property int $category_content_id
 * @property int $group_category_id
 * @property int $creator_id
 * @property int|null $editor_id
 * @property string $event_date
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $Creator
 * @property-read \App\Models\User|null $Editor
 * @property-read \App\Models\Event $Event
 * @property-read \App\Models\Person $Person
 * @method static \Database\Factories\FamilyEventFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyEvent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyEvent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyEvent onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyEvent query()
 
 * @mixin \Eloquent
 */
class FamilyEvent extends Eloquent
{
    protected $fillable = [
        'person_id',
        'event_id',
        'category_content_id',
        'group_category_id',
        'creator_id',
        'editor_id',
        'event_date',
        'status',
    ];
    use HasFactory;
    use SoftDeletes;

    public const TABLE_NAME = 'family_events';
    protected $table = self::TABLE_NAME;

    public const COLUMN_ID = 'id';
    public const COLUMN_CREATOR_ID = 'creator_id';
    public const COLUMN_EDITOR_ID = 'editor_id';

    public const COLUMN_PERSON_ID = 'person_id';
    public const COLUMN_EVENT_ID = 'event_id';
    public const COLUMN_CATEGORY_CONTENT_ID = 'category_content_id';
    public const COLUMN_GROUP_CATEGORY_ID = 'group_category_id';

    public function Person()
    {
        return $this->belongsTo(Person::class, self::COLUMN_PERSON_ID);
    }
    public function Event()
    {
        return $this->belongsTo(Event::class, self::COLUMN_EVENT_ID);
    }
    public function CategoryContent()
    {
        return $this->belongsTo(CategoryContent::class, self::COLUMN_CATEGORY_CONTENT_ID);
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
