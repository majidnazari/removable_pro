<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Country;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $json = File::get(database_path('datasample/countries.json')); // Assuming the JSON is stored in the database/data directory
        $countries = json_decode($json, true); // Decode JSON into an array
        // Create an array to hold all the countries data
        $countryData = [];

        // Loop through the countries and add them to the $countryData array
        foreach ($countries as $country) {
            // Check if faName exists, else use enName
            $title = !empty($country['faName']) ? $country['faName'] : $country['enName'];

            // Add the country data to the array
            $countryData[] = [
                'title' => $title, // Set faName or enName to title
                'code' => $country['code'], // Map code to code
                //'created_at' => $country['created_at'],
                //'updated_at' => $country['updated_at'],
            ];
        }

        // Insert all the country data at once
        DB::table('countries')->insert($countryData);

    }
}
