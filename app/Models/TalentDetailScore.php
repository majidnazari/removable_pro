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
 * @property int $participating_user_id
 * @property int $talent_detail_id
 * @property int $score 0=None 1=One 2=Two 3=Three 4-Four 5=Five 6=Six 7=Seven 8=Eight 9=Nine 10=Ten
 * @property int $status 1=Active 2=Inactive
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TalentDetailScore newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TalentDetailScore newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TalentDetailScore query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TalentDetailScore whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TalentDetailScore whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TalentDetailScore whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TalentDetailScore whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TalentDetailScore whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TalentDetailScore whereParticipatingUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TalentDetailScore whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TalentDetailScore whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TalentDetailScore whereTalentDetailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TalentDetailScore whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TalentDetailScore extends Eloquent
{
    protected $fillable = [
        'creator_id',
        'editor_id',
        'talent_detail_id',
        'participating_user_id',
        'score',
        'status',

    ];
    use HasFactory;
    use SoftDeletes;

    public const TABLE_NAME = 'talent_detail_scores';
    protected $table = self::TABLE_NAME;
    public const COLUMN_ID = 'id';
    public const COLUMN_CREATOR_ID = 'creator_id';
    public const COLUMN_EDITOR_ID = 'editor_id';

    public const COLUMN_TALENT_DETAIL_ID = 'talent_detail_id';
    public const COLUMN_PARTICIPATE_USER_ID = 'participating_user_id';


    public function Creator()
    {
        return $this->belongsTo(User::class, self::COLUMN_CREATOR_ID);
    }
    public function Editor()
    {
        return $this->belongsTo(User::class, self::COLUMN_EDITOR_ID);
    }
    public function ParticipateUser()
    {
        return $this->belongsTo(User::class, self::COLUMN_PARTICIPATE_USER_ID);
    }
    public function TalentDetail()
    {
        return $this->belongsTo(TalentDetail::class, self::COLUMN_TALENT_DETAIL_ID);
    }

    public static function getAuthorizationColumns()
    {
        return [
            "creator_id",
        ];
    }

}
