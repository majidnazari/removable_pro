<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;



class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(database_path('datasample/provinces.json')); 
        $provinces = json_decode($json, true); // Decode JSON into an array
      
        $provinceData = [];
        foreach ($provinces as $province) {
           
            $title = !empty($province['faName']) ? $province['faName'] : $province['enName'];
            $provinceData[] = [
                'title' => $title, 
                'country_id' => $province['countryId'], 
            ];
        }
       
        DB::table('provinces')->insert($provinceData);

    }
}
