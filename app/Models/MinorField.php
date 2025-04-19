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
 * @property int $middle_field_id
 * @property string $title
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MinorField newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MinorField newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MinorField query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MinorField whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MinorField whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MinorField whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MinorField whereMiddleFieldId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MinorField whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MinorField whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MinorField extends Eloquent
{
    protected $fillable = [
        'creator_id',
        'middle_field_id',
        'title',
    ];
    use HasFactory;
    use SoftDeletes;

    public const TABLE_NAME = 'minor_fields';
    protected $table = self::TABLE_NAME;
    public const COLUMN_ID = 'id';
    public const COLUMN_MIDDLE_FIELD_ID = 'middle_field_id';
    public const COLUMN_MINOR_FIELD_ID = 'minor_field_id';

    // public const COLUMN_Middle_FIELD_ID = 'middle_field_id';

    // public function MiddleFields()
    // {
    //     return $this->hasMany(MajorField::class, self::COLUMN_Middle_FIELD_ID);
    // }
    public function MiddleField()
    {
        return $this->belongsTo(MiddleField::class, self::COLUMN_MIDDLE_FIELD_ID);
    }

    public function TalentDetails()
    {
        return $this->hasMany(TalentDetail::class, self::COLUMN_MINOR_FIELD_ID);
    }

    public static function getAuthorizationColumns()
    {
        return [
            "creator_id",
        ];
    }
}
