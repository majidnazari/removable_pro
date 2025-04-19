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
 
 * @mixin \Eloquent
 */
class PersonScore extends Eloquent
{
    protected $fillable = [
        'creator_id',
        'editor_id',
        'person_id',
        'score_id',
        'score_level',
        'status',
    ];
    use HasFactory;
    use SoftDeletes;

    public const TABLE_NAME = 'person_scores';
    protected $table = self::TABLE_NAME;

    public const COLUMN_ID = 'id';
    public const COLUMN_CREATOR_ID = 'creator_id';
    public const COLUMN_EDITOR_ID = 'editor_id';

    public const COLUMN_PERSON_ID = 'person_id';
    public const COLUMN_SCORE_ID = 'score_id';

    public function Person()
    {
        return $this->belongsTo(Person::class, self::COLUMN_PERSON_ID);
    }

    public function Creator()
    {
        return $this->belongsTo(User::class, self::COLUMN_CREATOR_ID);
    }

    public function Editor()
    {
        return $this->belongsTo(User::class, self::COLUMN_EDITOR_ID);
    }
    public function Score()
    {
        return $this->belongsTo(Score::class, self::COLUMN_SCORE_ID);
    }

    public static function getAuthorizationColumns()
    {
        return [
            "creator_id",
        ];
    }
}
