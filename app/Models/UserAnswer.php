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
 * @property int $user_id
 * @property int $question_id
 * @property string $answer
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $Creator
 * @property-read \App\Models\Question $Question
 * @property-read \App\Models\User $User
 * @method static \Database\Factories\UserAnswerFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAnswer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAnswer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAnswer onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAnswer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAnswer whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAnswer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAnswer whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAnswer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAnswer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAnswer whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAnswer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAnswer whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAnswer withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAnswer withoutTrashed()
 * @mixin \Eloquent
 */
class UserAnswer extends \Eloquent
{
    protected $fillable = [
        'creator_id',
        'user_id',
        'question_id',
        'answer',
    ];
    use HasFactory, SoftDeletes;
    public const TABLE_NAME = 'user_answers';
    public const COLUMN_ID = 'id';
    public const CREATOR_ID = 'creator_id';
    public const EDITOR_ID = 'editor_id';

    public const USER_ID = 'user_id';
    public const QUESTION_ID = 'question_id';
    protected $table = self::TABLE_NAME;

    public function Creator()
    {
        return $this->belongsTo(User::class, self::CREATOR_ID);
    }
    public function User()
    {
        return $this->belongsTo(User::class, self::USER_ID);
    }
    public function Question()
    {
        return $this->belongsTo(Question::class, self::QUESTION_ID);
    }
}
