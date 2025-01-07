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
 * @property int $major_field_id
 * @property string $title
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MiddleField newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MiddleField newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MiddleField query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MiddleField whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MiddleField whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MiddleField whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MiddleField whereMajorFieldId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MiddleField whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MiddleField whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MiddleField extends Eloquent
{
    protected $fillable = [
        'creator_id',
        'major_field_id',
        'title',
    ];
    use HasFactory;
    use SoftDeletes;

    public const TABLE_NAME = 'middle_fields';
    protected $table = self::TABLE_NAME;
    public const COLUMN_ID = 'id';
    public const COLUMN_MAJOR_FIELD_ID = 'major_field_id';
    

    public function MajorField()
    {
        return $this->belongsTo(MajorField::class, self::COLUMN_MAJOR_FIELD_ID);
    }

    public static function getAuthorizationColumns()
    {
        return [
            "creator_id", 
        ];
    }
}
