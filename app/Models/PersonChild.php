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
 
 * @mixin \Eloquent
 */
class PersonChild extends Eloquent
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
    use HasFactory;
    use SoftDeletes;

    public const TABLE_NAME = 'person_children';
    protected $table = self::TABLE_NAME;

    public const COLUMN_ID = 'id';
    public const COLUMN_CREATOR_ID = 'creator_id';
    public const COLUMN_EDITOR_ID = 'editor_id';

    public const COLUMN_PERSON_MARRIAGE_ID = 'person_marriage_id';
    public const COLUMN_CHILD_ID = 'child_id';

    public function Creator()
    {
        return $this->belongsTo(User::class, self::COLUMN_CREATOR_ID);
    }
    public function Editor()
    {
        return $this->belongsTo(User::class, self::COLUMN_EDITOR_ID);
    }

    public function PersonMarriage()
    {
        return $this->belongsTo(PersonMarriage::class, self::COLUMN_PERSON_MARRIAGE_ID);
    }
    public function WhoseChild()
    {
        return $this->belongsTo(Person::class, self::COLUMN_CHILD_ID);
    }

    public static function getAuthorizationColumns()
    {
        return [
            "creator_id",
        ];
    }

}
