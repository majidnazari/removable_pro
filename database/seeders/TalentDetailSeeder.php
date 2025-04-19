<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class TalentDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = database_path('datasample/talentdetail.json');

        if (File::exists($jsonPath)) {
            $jsonData = json_decode(File::get($jsonPath), true);

            if (!empty($jsonData)) {
                DB::table('talent_details')->insert($jsonData);
            } else {
                $this->command->info("No valid records to insert.");
            }
        } else {
            $this->command->info("JSON file not found at {$jsonPath}");
        }
    }
}
