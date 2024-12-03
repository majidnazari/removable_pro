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
 * @property int $man_id
 * @property int $woman_id
 * @property int $creator_id
 * @property int|null $editor_id
 * @property string $marriage_status
 * @property string $status
 * @property string|null $marriage_date
 * @property string|null $divorce_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Person> $Children
 * @property-read int|null $children_count
 * @property-read \App\Models\User $Creator
 * @property-read \App\Models\User|null $Editor
 * @property-read \App\Models\Person $Man
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PersonChild> $PersonChild
 * @property-read int|null $person_child_count
 * @property-read \App\Models\Person $Woman
 * @method static \Database\Factories\PersonMarriageFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonMarriage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonMarriage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonMarriage onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonMarriage query()

 * @mixin \Eloquent
 */
class PersonMarriage extends Eloquent
{
    protected $fillable = [
        'man_id',
        'woman_id',
        'creator_id',
        'editor_id',
        'marriage_status',
        'status',
        'marriage_date',
        'divorce_date',
    ];
    use HasFactory;
    use SoftDeletes;

    public const TABLE_NAME = 'person_marriages';
    protected $table = self::TABLE_NAME;
    public const COLUMN_ID = 'id';
    public const COLUMN_CREATOR_ID = 'creator_id';
    public const COLUMN_EDITOR_ID = 'editor_id';

    public const COLUMN_MAN_ID = 'man_id';
    public const COLUMN_WOMAN_ID = 'woman_id';
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

    public function Man()
    {
        return $this->belongsTo(Person::class, self::COLUMN_MAN_ID);
    }

    public function Woman()
    {
        return $this->belongsTo(Person::class, self::COLUMN_WOMAN_ID);
    }

    public function PersonChild()
    {
        return $this->hasMany(PersonChild::class, self::COLUMN_PERSON_MARRIAGE_ID);
    }

    public function Children()
    {
        return $this->hasManyThrough(
            Person::class,
            PersonChild::class,
            self::COLUMN_PERSON_MARRIAGE_ID, // Foreign key on the `PersonChild` table
            self::COLUMN_ID,               // Foreign key on the `Person` table
            self::COLUMN_ID,               // Local key on the `PersonMarriage` table
            self::COLUMN_CHILD_ID          // Local key on the `PersonChild` table
        );
    }

    public static function getAuthorizationColumns()
    {
        return [
            "creator_id", 
        ];
    }

}
