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
 * @property int $group_category_id
 * @property int $person_id
 * @property string $title
 * @property string $end_date
 * @property int $status 1=Active 2=Inactive
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TalentHeader newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TalentHeader newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TalentHeader query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TalentHeader whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TalentHeader whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TalentHeader whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TalentHeader whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TalentHeader whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TalentHeader whereGroupCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TalentHeader whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TalentHeader wherePersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TalentHeader whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TalentHeader whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TalentHeader whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TalentHeader extends Eloquent
{
    protected $fillable = [
        'creator_id',
        'editor_id',
        'group_category_id',
        'person_id',
        'title',
        'end_date',
        'status',
    ];
    use HasFactory;
    use SoftDeletes;

    public const TABLE_NAME = 'talent_headers';
    protected $table = self::TABLE_NAME;
    public const COLUMN_ID = 'id';
    public const COLUMN_CREATOR_ID = 'creator_id';
    public const COLUMN_EDITOR_ID = 'editor_id';

    public const COLUMN_GROUP_CATEGORY_ID = 'group_category_id';
    

    public function Creator()
    {
        return $this->belongsTo(User::class, self::COLUMN_CREATOR_ID);
    }

    public function Editor()
    {
        return $this->belongsTo(User::class, self::COLUMN_EDITOR_ID);
    }
    public function GroupCategory()
    {
        return $this->belongsTo(GroupCategory::class, self::COLUMN_GROUP_CATEGORY_ID);
    }
}
