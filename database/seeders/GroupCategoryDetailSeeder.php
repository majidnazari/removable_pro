<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Carbon;

class GroupCategoryDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Path to the JSON file
        $jsonFilePath = database_path('datasample/groupcategorydetails.json');

        // Check if the file exists
        if (File::exists($jsonFilePath)) {
            // Get the contents of the JSON file
            $json = File::get($jsonFilePath);

            // Decode the JSON data into an array
            $data = json_decode($json, true);

            // Loop through each record and insert it into the database
            foreach ($data as $record) {
                DB::table('group_category_details')->insert([
                    'id' => $record['id'],
                    'creator_id' => $record['creator_id'],
                    'editor_id' => $record['editor_id'],
                    'group_category_id' => $record['group_category_id'],
                    'group_id' => $record['group_id'],
                    'status' => $record['status'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $this->command->info('group_category_details seeded successfully.');
        } else {
            $this->command->error('JSON file not found at ' . $jsonFilePath);
        }
    }
}
