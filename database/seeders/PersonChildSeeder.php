<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PersonChild;
use App\Models\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PersonChildSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    //public function run(): void
    //{
    //
    // $data=
    // [

    //     [
    //         'creator_id' => 1,
    //         'editor_id' => null,
    //         'person_marriage_id' => 1,
    //         'child_id' => 3,
    //         'child_kind' => "Direct_child",
    //         'child_status' => "With_family",

    //         'status' => 'Active',
    //         'created_at' => now(),
    //         'updated_at' => now(),
    //     ],
    //     [
    //         'creator_id' => 1,
    //         'editor_id' => null,
    //         'person_marriage_id' => 1,
    //         'child_id' => 4,
    //         'child_kind' => "Direct_child",
    //         'child_status' => "With_family",

    //         'status' => 'Active',
    //         'created_at' => now(),
    //         'updated_at' => now(),
    //     ],
    // ];

    // PersonChild::insert($data);


    //}
    public function run()
    {
        // $data = [
        //     ["id" => 1, "creator_id" => 1, "editor_id" => 1, "person_marriage_id" => 1, "child_id" => 3, "child_kind" => "Direct_child", "child_status" => "With_family", "status" => "Active", "created_at" => "2024-10-23 16:16:04", "updated_at" => "2024-10-27 11:13:52", "deleted_at" => null],
        //     ["id" => 2, "creator_id" => 1, "editor_id" => null, "person_marriage_id" => 1, "child_id" => 4, "child_kind" => "Direct_child", "child_status" => "With_family", "status" => "Active", "created_at" => "2024-10-23 16:16:04", "updated_at" => "2024-10-23 16:16:04", "deleted_at" => null],
        //     ["id" => 3, "creator_id" => 1, "editor_id" => null, "person_marriage_id" => 3, "child_id" => 8, "child_kind" => "Direct_child", "child_status" => "With_family", "status" => "Active", "created_at" => "2024-10-23 16:16:04", "updated_at" => "2024-10-23 16:16:04", "deleted_at" => null],
        //     ["id" => 4, "creator_id" => 1, "editor_id" => null, "person_marriage_id" => 5, "child_id" => 12, "child_kind" => "Direct_child", "child_status" => "With_family", "status" => "Active", "created_at" => "2024-10-23 16:16:04", "updated_at" => "2024-10-23 16:16:04", "deleted_at" => null],
        //     ["id" => 5, "creator_id" => 1, "editor_id" => null, "person_marriage_id" => 6, "child_id" => 13, "child_kind" => "Direct_child", "child_status" => "With_family", "status" => "Active", "created_at" => "2024-10-23 16:16:04", "updated_at" => "2024-10-23 16:16:04", "deleted_at" => null],
        //     ["id" => 6, "creator_id" => 1, "editor_id" => null, "person_marriage_id" => 2, "child_id" => 1, "child_kind" => "Direct_child", "child_status" => "With_family", "status" => "Active", "created_at" => "2024-10-23 16:16:04", "updated_at" => "2024-10-23 16:16:04", "deleted_at" => null],
        //     ["id" => 7, "creator_id" => 1, "editor_id" => null, "person_marriage_id" => 8, "child_id" => 5, "child_kind" => "Direct_child", "child_status" => "With_family", "status" => "Active", "created_at" => "2024-10-23 16:16:04", "updated_at" => "2024-10-23 16:16:04", "deleted_at" => null],
        //     ["id" => 8, "creator_id" => 1, "editor_id" => null, "person_marriage_id" => 7, "child_id" => 6, "child_kind" => "Direct_child", "child_status" => "With_family", "status" => "Active", "created_at" => "2024-10-23 16:16:04", "updated_at" => "2024-10-23 16:16:04", "deleted_at" => null]
        // ];

        // $data = [
        //     ["id" => 1, "creator_id" => 1, "editor_id" => null, "person_marriage_id" => 1, "child_id" => 3, "child_kind" => "Direct_child", "child_status" => "With_family", "status" => "Active", "created_at" => "2024-10-24 10:05:08", "updated_at" => "2024-10-24 10:05:08", "deleted_at" => null],
        //     ["id" => 2, "creator_id" => 1, "editor_id" => null, "person_marriage_id" => 1, "child_id" => 4, "child_kind" => "Direct_child", "child_status" => "With_family", "status" => "Active", "created_at" => "2024-10-24 10:05:08", "updated_at" => "2024-10-24 10:05:08", "deleted_at" => null],
        //     ["id" => 3, "creator_id" => 1, "editor_id" => null, "person_marriage_id" => 3, "child_id" => 9, "child_kind" => "Direct_child", "child_status" => "With_family", "status" => "Active", "created_at" => "2024-10-24 11:20:27", "updated_at" => "2024-10-24 11:20:27", "deleted_at" => null],
        //     ["id" => 4, "creator_id" => 1, "editor_id" => null, "person_marriage_id" => 4, "child_id" => 13, "child_kind" => "Direct_child", "child_status" => "With_family", "status" => "Active", "created_at" => "2024-10-24 11:20:47", "updated_at" => "2024-10-24 11:20:47", "deleted_at" => null],
        //     ["id" => 5, "creator_id" => 1, "editor_id" => null, "person_marriage_id" => 5, "child_id" => 10, "child_kind" => "Direct_child", "child_status" => "With_family", "status" => "Active", "created_at" => "2024-10-24 11:21:12", "updated_at" => "2024-10-24 11:21:12", "deleted_at" => null],
        //     ["id" => 6, "creator_id" => 1, "editor_id" => null, "person_marriage_id" => 6, "child_id" => 14, "child_kind" => "Direct_child", "child_status" => "With_family", "status" => "Active", "created_at" => "2024-10-24 11:21:39", "updated_at" => "2024-10-24 11:21:39", "deleted_at" => null],
        //     ["id" => 7, "creator_id" => 1, "editor_id" => null, "person_marriage_id" => 6, "child_id" => 15, "child_kind" => "Direct_child", "child_status" => "With_family", "status" => "Active", "created_at" => "2024-10-24 11:21:42", "updated_at" => "2024-10-24 11:21:42", "deleted_at" => null],
        //     ["id" => 8, "creator_id" => 1, "editor_id" => null, "person_marriage_id" => 7, "child_id" => 18, "child_kind" => "Direct_child", "child_status" => "With_family", "status" => "Active", "created_at" => "2024-10-24 11:22:02", "updated_at" => "2024-10-24 11:22:02", "deleted_at" => null],
        //     ["id" => 9, "creator_id" => 1, "editor_id" => null, "person_marriage_id" => 8, "child_id" => 20, "child_kind" => "Direct_child", "child_status" => "With_family", "status" => "Active", "created_at" => null, "updated_at" => null, "deleted_at" => null],
        //     ["id" => 10, "creator_id" => 1, "editor_id" => null, "person_marriage_id" => 9, "child_id" => 32, "child_kind" => "Direct_child", "child_status" => "With_family", "status" => "Active", "created_at" => null, "updated_at" => null, "deleted_at" => null],
        //     ["id" => 11, "creator_id" => 1, "editor_id" => null, "person_marriage_id" => 10, "child_id" => 36, "child_kind" => "Direct_child", "child_status" => "With_family", "status" => "Active", "created_at" => null, "updated_at" => null, "deleted_at" => null],
        //     ["id" => 12, "creator_id" => 1, "editor_id" => null, "person_marriage_id" => 11, "child_id" => 41, "child_kind" => "Direct_child", "child_status" => "With_family", "status" => "Active", "created_at" => null, "updated_at" => null, "deleted_at" => null],
        //     ["id" => 13, "creator_id" => 1, "editor_id" => null, "person_marriage_id" => 12, "child_id" => 17, "child_kind" => "Direct_child", "child_status" => "With_family", "status" => "Active", "created_at" => null, "updated_at" => null, "deleted_at" => null],
        //     ["id" => 14, "creator_id" => 1, "editor_id" => null, "person_marriage_id" => 12, "child_id" => 44, "child_kind" => "Direct_child", "child_status" => "With_family", "status" => "Active", "created_at" => null, "updated_at" => null, "deleted_at" => null]
        // ];
        

        // foreach ($data as $personChild) {
        //     PersonChild::create($personChild);
        // }

          $jsonPath = database_path('datasample/personchild.json');

          if (File::exists($jsonPath)) {
              $jsonData = json_decode(File::get($jsonPath), true);
  
              // Filter records to ensure `creator_id` and `editor_id` exist in the `users` table
              $validUsers = User::pluck('id')->toArray();
              $filteredData = array_filter($jsonData, function ($person) use ($validUsers) {
                  return in_array($person['creator_id'], $validUsers) && 
                          (empty($person['editor_id']) || in_array($person['editor_id'], $validUsers));
              });
  
              if (!empty($filteredData)) {
                  DB::table('person_children')->insert($filteredData);
              } else {
                  $this->command->info("No valid records to insert.");
              }
          } else {
              $this->command->info("JSON file not found at {$jsonPath}");
          }
    }
}
