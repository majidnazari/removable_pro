<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Carbon;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Path to the JSON file
        $jsonFilePath = database_path('datasample/groups.json');

        // Check if the file exists
        if (File::exists($jsonFilePath)) {
            // Get the contents of the JSON file
            $json = File::get($jsonFilePath);

            // Decode the JSON data into an array
            $data = json_decode($json, true);

            // Loop through each record and insert it into the database
            foreach ($data as $record) {
                DB::table('groups')->insert([
                    'id' => $record['id'],
                    'creator_id' => $record['creator_id'],
                    'editor_id' => $record['editor_id'],
                    'title' => $record['title'],

                    'status' => $record['status'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $this->command->info('groups seeded successfully.');
        } else {
            $this->command->error('JSON file not found at ' . $jsonFilePath);
        }
    }
}
