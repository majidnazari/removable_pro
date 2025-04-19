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
 * @property int $country_id
 * @property string $title
 * @property string|null $code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Country $Country
 * @method static \Database\Factories\ProvinceFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Province query()
 
 * @mixin \Eloquent
 */
class Province extends Eloquent
{
    protected $fillable = [
        'country_id',
        'title',
        'code',
    ];
    use HasFactory;
    use SoftDeletes;

    public const TABLE_NAME = 'provinces';
    protected $table = self::TABLE_NAME;

    public const COLUMN_ID = 'id';
    public const COLUMN_CREATOR_ID = 'creator_id';
    public const COLUMN_EDITOR_ID = 'editor_id';

    public const COLUMN_COUNTRY_ID = 'country_id';

    public function Country()
    {
        return $this->belongsTo(Country::class, self::COLUMN_COUNTRY_ID);
    }

    public static function getAuthorizationColumns()
    {
        return [
            "creator_id",
        ];
    }

}


