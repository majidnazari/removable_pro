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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MicroField newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MicroField newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MicroField query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MicroField whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MicroField whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MicroField whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MicroField whereMiddleFieldId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MicroField whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MicroField whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MicroField extends Eloquent
{
    protected $fillable = [
        'creator_id',
        'middle_field_id',
        'title',
    ];
    use HasFactory;
    use SoftDeletes;

    public const TABLE_NAME = 'micro_fields';
    protected $table = self::TABLE_NAME;
    public const COLUMN_ID = 'id';
    public const COLUMN_MICRO_FIELD_ID = 'middle_field_id';
    

    public function MicroField()
    {
        return $this->belongsTo(MicroField::class, self::COLUMN_MICRO_FIELD_ID);
    }


    public static function getAuthorizationColumns()
    {
        return [
            "creator_id", 
        ];
    }
}
