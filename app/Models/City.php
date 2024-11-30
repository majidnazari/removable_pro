<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Eloquent;


/**
 * 
 *
 * @property int $id
 * @property int $province_id
 * @property string $title
 * @property string|null $code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Province $Area
 * @property-read \App\Models\Province $Province
 * @method static \Database\Factories\CityFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|City query()
 
 * @mixin \Eloquent
 */
class City extends Eloquent
{
    protected $fillable = [
        'province_id',
        'title',
        'code',
    ];
    use HasFactory;
    use SoftDeletes;


    public const TABLE_NAME = 'cities';
    protected $table = self::TABLE_NAME;

    public const COLUMN_ID = 'id';
    public const COLUMN_CREATOR_ID = 'creator_id';
    public const COLUMN_EDITOR_ID = 'editor_id';

    public const COLUMN_PROVINCE_ID = 'province_id';


    public function Province()
    {
        return $this->belongsTo(Province::class, self::COLUMN_PROVINCE_ID);
    }
    // public function Area()
    // {
    //     return $this->belongsTo(Province::class, 'province_id');
    // }
}


