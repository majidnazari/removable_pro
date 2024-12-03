<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Eloquent;

/**
 * 
 *
 * @property int $id
 * @property int $creator_id
 * @property int|null $editor_id
 * @property int $person_id
 * @property int|null $country_id
 * @property int|null $province_id
 * @property int|null $city_id
 * @property int|null $area_id
 * @property string|null $location_title
 * @property string|null $street_name
 * @property int|null $builder_no
 * @property int|null $floor_no
 * @property int|null $unit_no
 * @property string|null $lat
 * @property string|null $lon
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Area|null $Area
 * @property-read \App\Models\City|null $City
 * @property-read \App\Models\Country|null $Country
 * @property-read \App\Models\User $Creator
 * @property-read \App\Models\User|null $Editor
 * @property-read \App\Models\Person $Person
 * @property-read \App\Models\Province|null $Province
 * @method static \Database\Factories\AddressFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address withoutTrashed()
 * @mixin \Eloquent
 
 */
class Address extends Eloquent
{
    protected $fillable = [
        'creator_id',
        'editor_id',
        'person_id',
        'country_id',
        'province_id',
        'city_id',
        'area_id',
        'location_title',
        'street_name',
        'builder_no',
        'floor_no',
        'unit_no',
        'lat',
        'lon',
        'status',
    ];
    use HasFactory;
    use SoftDeletes;

    // protected $casts = [
    //     'status' => Status::class,
    // ];


    public const TABLE_NAME = 'addresses';
    protected $table = self::TABLE_NAME;

    public const COLUMN_ID = 'id';
    public const COLUMN_CREATOR_ID = 'creator_id';
    public const COLUMN_EDITOR_ID = 'editor_id';
    public const COLUMN_PERSON_ID = 'person_id';
    public const COLUMN_AREA_ID = 'area_id';
    public const COLUMN_CITY_ID = 'city_id';
    public const COLUMN_PROVINCE_ID = 'province_id';
    public const COLUMN_COUNTRY_ID = 'country_id';


    public function Creator()
    {
        return $this->belongsTo(User::class, self::COLUMN_CREATOR_ID);
    }

    public function Editor()
    {
        return $this->belongsTo(User::class, self::COLUMN_EDITOR_ID);
    }

    public function Person()
    {
        return $this->belongsTo(Person::class, self::COLUMN_PERSON_ID);
    }

    public function Area()
    {
        return $this->belongsTo(Area::class, self::COLUMN_AREA_ID);
    }
    public function City()
    {
        return $this->belongsTo(City::class, self::COLUMN_CITY_ID);
    }

    public function Province()
    {
        return $this->belongsTo(Province::class, self::COLUMN_PROVINCE_ID);
    }
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
