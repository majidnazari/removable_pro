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
 
 * @mixin \Eloquent
 */
class PersonDetail extends Eloquent
{
    protected $fillable = [
        'person_id',
        'profile_picture',
        'physical_condition',
    ];
    use HasFactory;
    use SoftDeletes;

    public const TABLE_NAME = 'person_details';
    protected $table = self::TABLE_NAME;

    public const COLUMN_COLUMN_ID = 'id';
    public const COLUMN_CREATOR_ID = 'creator_id';
    public const COLUMN_EDITOR_ID = 'editor_id';

    public const COLUMN_PERSON_ID = 'person_id';

    public function Person()
    {
        return $this->belongsTo(Person::class, self::COLUMN_PERSON_ID);
    }
}
