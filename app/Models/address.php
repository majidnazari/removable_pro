<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class address extends Model
{
    protected $fillable = ['creator_id', 'editor_id', 'person_id', 'country_id', 'province_id', 'city_id', 'area_id', 'location_title', 'street_name', 'builder_no', 'floor_no', 'unit_no', 'lat', 'lon', 'status'];
    use HasFactory,SoftDeletes;

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'editor_id');
    }

    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
