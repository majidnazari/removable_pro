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
 * @property int $person_id
 * @property string|null $image
 * @property string|null $title
 * @property string|null $description
 * @property string|null $star
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $Creator
 * @property-read \App\Models\User|null $Editor
 * @property-read \App\Models\Person $Person
 * @method static \Database\Factories\FavoriteFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite wherePersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereStar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite withoutTrashed()
 * @mixin \Eloquent
 */
class Favorite extends \Eloquent
{
    protected $fillable = [
        'creator_id',
        'editor_id',
        'person_id',
        'image',
        'title',
        'description',
        'star',
        'status',
    ];
    use HasFactory, SoftDeletes;
    public const TABLE_NAME = 'favorites';
    public const COLUMN_ID = 'id';
    public const CREATOR_ID = 'creator_id';
    public const EDITOR_ID = 'editor_id';

    public const PERSON_ID = 'person_id';
    protected $table = self::TABLE_NAME;

    public function Person()
    {
        return $this->belongsTo(Person::class, self::PERSON_ID);
    }

    public function Creator()
    {
        return $this->belongsTo(User::class, self::CREATOR_ID);
    }

    public function Editor()
    {
        return $this->belongsTo(User::class, self::EDITOR_ID);
    }
}
