<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Carbon;

class UserMergeRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Path to the JSON file
        $jsonFilePath = database_path('datasample/usermergerequest.json');

        // Check if the file exists
        if (File::exists($jsonFilePath)) {
            // Get the contents of the JSON file
            $json = File::get($jsonFilePath);

            // Decode the JSON data into an array
            $data = json_decode($json, true);

            // Loop through each record and insert it into the database
            foreach ($data as $record) {
                DB::table('user_merge_requests')->insert([
                    'id' => $record['id'],
                    'creator_id' => $record['creator_id'],
                    'editor_id' => $record['editor_id'],
                    'user_sender_id' => $record['user_sender_id'],
                    'node_sender_id' => $record['node_sender_id'],
                    'user_receiver_id' => $record['user_receiver_id'],
                    'node_receiver_id' => $record['node_receiver_id'],
                    'request_status_sender' => $record['request_status_sender'],
                    'request_sender_expired_at' => Carbon::parse($record['request_sender_expired_at']),
                    'request_status_receiver' => $record['request_status_receiver'],
                    'merge_ids_sender' => $record['merge_ids_sender'],
                    'merge_ids_receiver' => $record['merge_ids_receiver'],
                    'merge_status_sender' => $record['merge_status_sender'],
                    'merge_sender_expired_at' => Carbon::parse($record['merge_sender_expired_at']),
                    'merge_status_receiver' => $record['merge_status_receiver'],
                    'status' => $record['status'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $this->command->info('User merge requests seeded successfully.');
        } else {
            $this->command->error('JSON file not found at ' . $jsonFilePath);
        }
    }
}
