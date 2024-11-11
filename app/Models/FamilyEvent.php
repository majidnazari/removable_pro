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
 * @property int $event_id
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyEvent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyEvent whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyEvent whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyEvent whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyEvent whereEventDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyEvent whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyEvent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyEvent wherePersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyEvent whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyEvent whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyEvent withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FamilyEvent withoutTrashed()
 * @mixin \Eloquent
 */
class FamilyEvent extends \Eloquent
{
    protected $fillable = [
        'person_id',
        'event_id',
        'creator_id',
        'editor_id',
        'event_date',
        'status',
    ];
    use HasFactory, SoftDeletes;

    public const TABLE_NAME = 'family_events';
    public const COLUMN_ID = 'id';
    public const CREATOR_ID = 'creator_id';
    public const EDITOR_ID = 'editor_id';
  
    public const PERSON_ID = 'person_id';
    public const EVENT_ID = 'event_id';
    protected $table = self::TABLE_NAME;

    public function Person()
    {
        return $this->belongsTo(Person::class, self::PERSON_ID);
    }
    public function Event()
    {
        return $this->belongsTo(Event::class, SELF::EVENT_ID);
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
