<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\GraphQL\Enums\Status;
use App\Traits\HasStatusEnum;

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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereBuilderNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereEditorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereFloorNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereLocationTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereLon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address wherePersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereProvinceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereStreetName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereUnitNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address withoutTrashed()
 * @mixin \Eloquent
 */
class Address extends \Eloquent  
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
    use HasFactory, SoftDeletes;// HasStatusEnum;

    // protected $casts = [
    //     'status' => Status::class,
    // ];


    public const TABLE_NAME = 'addresses';
    public const ID = 'id';
    public const CREATOR_ID = 'creator_id';
    public const EDITOR_ID = 'editor_id';
    public const PERSON_ID = 'person_id';
    public const AREA_ID = 'area_id';
    public const CITY_ID = 'city_id';
    public const PROVINCE_ID = 'province_id';
    public const COUNTRY_ID = 'country_id';

    protected $table = self::TABLE_NAME;

    public function Creator()
    {
        return $this->belongsTo(User::class, self::CREATOR_ID);
    }

    public function Editor()
    {
        return $this->belongsTo(User::class, self::EDITOR_ID);
    }

    public function Person()
    {
        return $this->belongsTo(Person::class, self::PERSON_ID);
    }

    public function Area()
    {
        return $this->belongsTo(Area::class, self::AREA_ID);
    }
    public function City()
    {
        return $this->belongsTo(City::class, self::CITY_ID);
    }

    public function Province()
    {
        return $this->belongsTo(Province::class, self::PROVINCE_ID);
    }
    public function Country()
    {
        return $this->belongsTo(Country::class, self::COUNTRY_ID);
    }

     // Accessor to get status as string
    
}
