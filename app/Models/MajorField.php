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
 * @property string $title
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 
 * @mixin \Eloquent
 */
class MajorField extends Eloquent
{

    protected $fillable = [
        'creator_id',
        'title',

    ];
    use HasFactory;
    use SoftDeletes;
    public const TABLE_NAME = 'major_fields';
    protected $table = self::TABLE_NAME;
    public const COLUMN_ID = 'id';
    public const COLUMN_MAJOR_FIELD_ID = 'major_field_id';

    public function MiddleFields()
    {
        return $this->hasMany(MiddleField::class, self::COLUMN_MAJOR_FIELD_ID);
    }

    public static function getAuthorizationColumns()
    {
        return [
            "creator_id",
        ];
    }

}
