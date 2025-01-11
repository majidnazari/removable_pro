<?php

namespace Database\Seeders;

use App\Models\major;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class MiddleFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(database_path('datasample/middlefield.json')); 
        $middles = json_decode($json, true); // Decode JSON into an array
      
        $middleData = [];
        foreach ($middles as $middle) {
           
            $title = !empty($middle['title']) ? $middle['title'] : null;
            $middleData[] = [
                'creator_id' => $middle['creator_id'],
                'title' => $title, 
                'major_field_id' => $middle['major_field_id']
            ];
        }
       
        DB::table('middle_fields')->insert($middleData);
    }
}
