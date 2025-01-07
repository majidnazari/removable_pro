<?php

namespace Database\Seeders;

use App\Models\major;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;


class MajorFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(database_path('datasample/majorfield.json')); 
        $majors = json_decode($json, true); // Decode JSON into an array
      
        $majorData = [];
        foreach ($majors as $major) {
           
            $title = !empty($major['title']) ? $major['title'] : null;
            $majorData[] = [
                'title' => $title, 
               
            ];
        }
       
        DB::table('major_fields')->insert($majorData);

    }
}
