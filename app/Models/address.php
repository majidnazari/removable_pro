<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class address extends Model
{
    protected $fillable = ['creator_id', 'editor_id', 'person_id', 'country_id', 'province_id', 'city_id', 'area_id', 'location_title', 'street_name', 'builder_no', 'floor_no', 'unit_no', 'lat', 'lon', 'status'];
    use HasFactory,SoftDeletes;
}
