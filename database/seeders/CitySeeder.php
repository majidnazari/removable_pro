<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\City;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;



class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(database_path('datasample/cities.json'));
        $cities = json_decode($json, true); // Decode JSON into an array

        $cityData = [];
        foreach ($cities as $city) {

            $title = !empty($city['faName']) ? $city['faName'] : $city['enName'];
            $cityData[] = [
                'title' => $title,
                //'country_id' => $city['countryId'], 
                'province_id' => $city['provinceId'],
            ];
        }

        DB::table('cities')->insert($cityData);
    }
}
