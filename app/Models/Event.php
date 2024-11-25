<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Eloquent;


/**
 * 
 *
 * @property int $id
 * @property string $title
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FamilyEvent> $FamilyEvents
 * @property-read int|null $family_events_count
 * @method static \Database\Factories\EventFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event query()

 * @mixin \Eloquent
 */
class Event extends Eloquent
{
    protected $fillable = [
        'title',
        'status',
    ];
    use HasFactory;
    use SoftDeletes;

    public const TABLE_NAME = 'events';
    protected $table = self::TABLE_NAME;

    public const COLUMN_ID = 'id';
    public const COLUMN_CREATOR_ID = 'creator_id';
    public const COLUMN_EDITOR_ID = 'editor_id';

    public const COLUMN_EVENT_ID = 'event_id';

    public function FamilyEvents()
    {
        return $this->hasMany(FamilyEvent::class, self::COLUMN_EVENT_ID);
    }

}
