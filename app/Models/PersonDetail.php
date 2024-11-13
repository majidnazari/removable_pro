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
 * @property string|null $profile_picture
 * @property string $physical_condition
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Person $Person
 * @method static \Database\Factories\PersonDetailFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDetail onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDetail whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDetail wherePersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDetail wherePhysicalCondition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDetail whereProfilePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDetail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDetail withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDetail withoutTrashed()
 * @mixin \Eloquent
 */
class PersonDetail extends \Eloquent
{
    protected $fillable = [
        'person_id',
        'profile_picture',
        'physical_condition',
    ];
    use HasFactory, SoftDeletes;

    public const TABLE_NAME = 'person_details';
    public const COLUMN_ID = 'id';
    public const CREATOR_ID = 'creator_id';
    public const EDITOR_ID = 'editor_id';

    public const PERSON_ID = 'person_id';
    protected $table = self::TABLE_NAME;

    public function Person()
    {
        return $this->belongsTo(Person::class, self::PERSON_ID);
    }
}
