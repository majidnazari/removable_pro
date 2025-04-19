<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class TalentHeaderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = database_path('datasample/talentheader.json');

        if (File::exists($jsonPath)) {
            $jsonData = json_decode(File::get($jsonPath), true);

            // // Filter records to ensure `creator_id` and `editor_id` exist in the `users` table
            // $validUsers = User::pluck('id')->toArray();
            // $filteredData = array_filter($jsonData, function ($person) use ($validUsers) {
            //     return in_array($person['creator_id'], $validUsers) && 
            //             (empty($person['editor_id']) || in_array($person['editor_id'], $validUsers));
            // });

            if (!empty($jsonData)) {
                DB::table('talent_headers')->insert($jsonData);
            } else {
                $this->command->info("No valid records to insert.");
            }
        } else {
            $this->command->info("JSON file not found at {$jsonPath}");
        }
    }
}
