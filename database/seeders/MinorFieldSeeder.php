<?php

namespace Database\Seeders;

use App\Models\major;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class MinorFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = database_path('datasample/minorfield.json');

        if (File::exists($jsonPath)) {
            $jsonData = json_decode(File::get($jsonPath), true);

            if (!empty($jsonData)) {
                DB::table('minor_fields')->insert($jsonData);
            } else {
                $this->command->info("No valid records to insert.");
            }
        } else {
            $this->command->info("JSON file not found at {$jsonPath}");
        }
    }
}
