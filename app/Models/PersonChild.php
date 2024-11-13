<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * 
 *
 * @property int $id
 * @property int $creator_id
 * @property int|null $editor_id
 * @property int $person_marriage_id
 * @property int $child_id
 * @property string $child_kind
 * @property string $child_status
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $Creator
 * @property-read \App\Models\User|null $Editor
 * @property-read \App\Models\PersonMarriage $PersonMarriage
 * @property-read \App\Models\Person $WhoseChild
 * @method static \Database\Factories\PersonChildFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonChild newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonChild newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonChild onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonChild query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonChild whereChildId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonChild whereChildKind($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonChild whereChildStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonChild whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonChild whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonChild whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonChild whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonChild whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonChild wherePersonMarriageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonChild whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonChild whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonChild withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonChild withoutTrashed()
 * @mixin \Eloquent
 */
class PersonChild extends \Eloquent
{
    protected $fillable = [
        'creator_id',
        'editor_id',
        'person_marriage_id',
        'child_id',
        'child_kind',
        'child_status',
        'status',
    ];
    use HasFactory, SoftDeletes;

    public const TABLE_NAME = 'person_children';
    public const COLUMN_ID = 'id';
    public const CREATOR_ID = 'creator_id';
    public const EDITOR_ID = 'editor_id';

    public const PERSON_MARRIAGE_ID = 'person_marriage_id';
    public const CHILD_ID = 'child_id';
    protected $table = self::TABLE_NAME;

    public function Creator()
    {
        return $this->belongsTo(User::class, self::CREATOR_ID);
    }
    public function Editor()
    {
        return $this->belongsTo(User::class, self::EDITOR_ID);
    }

    public function PersonMarriage()
    {
        return $this->belongsTo(PersonMarriage::class, self::PERSON_MARRIAGE_ID);
    }
    public function WhoseChild()
    {
        return $this->belongsTo(Person::class, self::CHILD_ID);
    }

}
