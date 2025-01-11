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
 * @property int $talent_header_id
 * @property int $minor_field_id
 * @property int $status 1=Active 2=Inactive
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TalentDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TalentDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TalentDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TalentDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TalentDetail whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TalentDetail whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TalentDetail whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TalentDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TalentDetail whereminorFieldId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TalentDetail whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TalentDetail whereTalentHeaderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TalentDetail whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TalentDetail extends Eloquent
{
    protected $fillable = [
        'creator_id',
        'editor_id',
        'talent_header_id',
        'minor_field_id',
        'status',
       
    ];
    use HasFactory;
    use SoftDeletes;

    public const TABLE_NAME = 'talent_details';
    protected $table = self::TABLE_NAME;
    public const COLUMN_ID = 'id';
    public const COLUMN_CREATOR_ID = 'creator_id';
    public const COLUMN_EDITOR_ID = 'editor_id';

    public const COLUMN_TALENT_HEADER_ID = 'talent_header_id';
    public const COLUMN_MINOR_FIELD_ID = 'minor_field_id';
    public const COLUMN_TALENT_DETAIL_ID = 'talent_detail_id';
   

    public function Creator()
    {
        return $this->belongsTo(User::class, self::COLUMN_CREATOR_ID);
    }
    public function Editor()
    {
        return $this->belongsTo(User::class, self::COLUMN_EDITOR_ID);
    }
    public function TalentHeader()
    {
        return $this->belongsTo(TalentHeader::class, self::COLUMN_TALENT_HEADER_ID);
    }
    public function MinorField()
    {
        return $this->belongsTo(MinorField::class, self::COLUMN_MINOR_FIELD_ID);
    }
    public function TalentDetailsScore()
    {
        return $this->hasMany(TalentDetailScore::class, self::COLUMN_TALENT_DETAIL_ID);
    }

    public static function getAuthorizationColumns()
    {
        return [
            "creator_id", 
        ];
    }
}
