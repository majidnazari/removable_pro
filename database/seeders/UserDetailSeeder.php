<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class UserDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $json = File::get(database_path('datasample/minorfield.json'));
        // $minors = json_decode($json, true); // Decode JSON into an array

        // $minorData = [];
        // foreach ($minors as $minor) {

        //     $title = !empty($minor['title']) ? $minor['title'] : null;
        //     $minorData[] = [
        //         'creator_id' => $minor['creator_id'],
        //         'title' => $title,
        //         'middle_field_id' => $minor['middle_field_id']

        //     ];
        // }

        // DB::table('minor_fields')->insert($minorData);
    }
}
