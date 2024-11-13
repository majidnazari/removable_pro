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
 * @property int $score_id
 * @property string $score_level
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $Creator
 * @property-read \App\Models\User|null $Editor
 * @property-read \App\Models\Person $Person
 * @property-read \App\Models\Score $Score
 * @method static \Database\Factories\PersonScoreFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonScore newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonScore newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonScore onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonScore query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonScore whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonScore whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonScore whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonScore whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonScore whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonScore wherePersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonScore whereScoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonScore whereScoreLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonScore whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonScore whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonScore withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonScore withoutTrashed()
 * @mixin \Eloquent
 */
class PersonScore extends \Eloquent
{
    protected $fillable = [
        'creator_id',
        'editor_id',
        'person_id',
        'score_id',
        'score_level',
        'status',
    ];
    use HasFactory, SoftDeletes;

    public const TABLE_NAME = 'person_scores';
    public const COLUMN_ID = 'id';
    public const CREATOR_ID = 'creator_id';
    public const EDITOR_ID = 'editor_id';

    public const PERSON_ID = 'person_id';
    public const SCORE_ID = 'score_id';
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
    public function Score()
    {
        return $this->belongsTo(Score::class, self::SCORE_ID);
    }
}
