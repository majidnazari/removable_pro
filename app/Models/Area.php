<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * 
 *
 * @property int $id
 * @property int $city_id
 * @property string $title
 * @property string|null $code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\City $City
 * @method static \Database\Factories\AreaFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area withoutTrashed()
 * @mixin \Eloquent
 */
class Area extends \Eloquent
{
    protected $fillable = [
        'city_id',
        'title',
        'code',
    ];
    use HasFactory, SoftDeletes;

    public const TABLE_NAME = 'areas';
    public const COLUMN_ID = 'id';
    public const CITY_ID = 'city_id';


    protected $table = self::TABLE_NAME;

    public function City()
    {
        return $this->belongsTo(City::class, self::CITY_ID);
    }

}
