<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\models\PersonMarriage;

class PersonMarriageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // public function run(): void
    // {
    //     //
    //     $data=[
    //         [
    //             'creator_id' => 1,
    //             'man_id' => 1,
    //             'woman_id' => 2,
    //             'marriage_status' => 'Related',
    //             // 'spouse_status' => 'Alive',
    //             'status' => 'Active',
    //             'marriage_date' => '2015-06-15 00:00:00',
    //             'divorce_date' => null,
    //             'created_at' => now(),                
    //         ],
    //         [
    //             'creator_id' => 1,
    //             'man_id' => 5,
    //             'woman_id' => 6,
    //             'marriage_status' => 'Related',
    //             // 'spouse_status' => 'Alive',
    //             'status' => 'Active',
    //             'marriage_date' => '1970-10-22 00:00:00',
    //             'divorce_date' => null,
    //             'created_at' => now(),               
    //         ],
    //         // [
    //         //     'man_id' => 5,
    //         //     'woman_id' => 6,
    //         //     'marriage_status' => 'Related',
    //         //     'spouse_status' => 'divorce',
    //         //     'status' => 'InActive',
    //         //     'marriage_date' => '2010-01-10 00:00:00',
    //         //     'divorce_date' => '2020-01-10 00:00:00',
    //         //     'created_at' => now(),
    //         //     'updated_at' => now(),
    //         // ],
    //         // [
    //         //     'man_id' => 7,
    //         //     'woman_id' => 8,
    //         //     'marriage_status' => 'Suspend',
    //         //     'spouse_status' => 'Unkown',
    //         //     'status' => 'Active',
    //         //     'marriage_date' => '2005-08-05 00:00:00',
    //         //     'divorce_date' => null,
    //         //     'created_at' => now(),
    //         //     'updated_at' => now(),
    //         // ],
    //     ];

    //     PersonMarriage::Insert($data);
    // }

    public function run()
    {
        // $data = [
        //     ["id" => 1, "man_id" => 1, "woman_id" => 2, "creator_id" => 1, "editor_id" => null, "marriage_status" => "Related", "status" => "Active", "marriage_date" => "2015-06-15 00:00:00", "divorce_date" => null, "created_at" => "2024-10-23 16:16:04", "updated_at" => null, "deleted_at" => null],
        //     ["id" => 2, "man_id" => 5, "woman_id" => 6, "creator_id" => 1, "editor_id" => null, "marriage_status" => "Related", "status" => "Active", "marriage_date" => "1970-10-22 00:00:00", "divorce_date" => null, "created_at" => "2024-10-23 16:16:04", "updated_at" => null, "deleted_at" => null],
        //     ["id" => 3, "man_id" => 1, "woman_id" => 7, "creator_id" => 1, "editor_id" => null, "marriage_status" => "Related", "status" => "Active", "marriage_date" => "2015-06-15 00:00:00", "divorce_date" => null, "created_at" => "2024-10-23 16:16:04", "updated_at" => null, "deleted_at" => null],
        //     ["id" => 5, "man_id" => 3, "woman_id" => 9, "creator_id" => 1, "editor_id" => null, "marriage_status" => "Related", "status" => "Active", "marriage_date" => "2015-06-15 00:00:00", "divorce_date" => null, "created_at" => "2024-10-23 16:16:04", "updated_at" => null, "deleted_at" => null],
        //     ["id" => 6, "man_id" => 10, "woman_id" => 4, "creator_id" => 1, "editor_id" => null, "marriage_status" => "Related", "status" => "Active", "marriage_date" => "2015-06-15 00:00:00", "divorce_date" => null, "created_at" => "2024-10-23 16:16:04", "updated_at" => null, "deleted_at" => null],
        //     ["id" => 7, "man_id" => 14, "woman_id" => 16, "creator_id" => 1, "editor_id" => null, "marriage_status" => "Related", "status" => "Active", "marriage_date" => "2015-06-15 00:00:00", "divorce_date" => null, "created_at" => "2024-10-23 16:16:04", "updated_at" => null, "deleted_at" => null],
        //     ["id" => 8, "man_id" => 17, "woman_id" => 18, "creator_id" => 1, "editor_id" => null, "marriage_status" => "Related", "status" => "Active", "marriage_date" => "2015-06-15 00:00:00", "divorce_date" => null, "created_at" => "2024-10-23 16:16:04", "updated_at" => null, "deleted_at" => null]
        // ];

        $data = [
            ["id" => 1, "man_id" => 1, "woman_id" => 2, "creator_id" => 1, "editor_id" => null, "marriage_status" => "Related", "status" => "Active", "marriage_date" => "2015-06-15 00:00:00", "divorce_date" => null, "created_at" => "2024-10-24 10:05:08", "updated_at" => null, "deleted_at" => null],
            ["id" => 2, "man_id" => 5, "woman_id" => 6, "creator_id" => 1, "editor_id" => null, "marriage_status" => "Related", "status" => "Active", "marriage_date" => "1970-10-22 00:00:00", "divorce_date" => null, "created_at" => "2024-10-24 10:05:08", "updated_at" => null, "deleted_at" => null],
            ["id" => 3, "man_id" => 7, "woman_id" => 8, "creator_id" => 1, "editor_id" => null, "marriage_status" => "None", "status" => "Active", "marriage_date" => null, "divorce_date" => null, "created_at" => "2024-10-24 11:18:30", "updated_at" => "2024-10-24 11:18:30", "deleted_at" => null],
            ["id" => 4, "man_id" => 9, "woman_id" => 10, "creator_id" => 1, "editor_id" => null, "marriage_status" => "None", "status" => "Active", "marriage_date" => null, "divorce_date" => null, "created_at" => "2024-10-24 11:18:42", "updated_at" => "2024-10-24 11:18:42", "deleted_at" => null],
            ["id" => 5, "man_id" => 11, "woman_id" => 12, "creator_id" => 1, "editor_id" => null, "marriage_status" => "None", "status" => "Active", "marriage_date" => null, "divorce_date" => null, "created_at" => "2024-10-24 11:18:53", "updated_at" => "2024-10-24 11:18:53", "deleted_at" => null],
            ["id" => 6, "man_id" => 13, "woman_id" => 16, "creator_id" => 1, "editor_id" => null, "marriage_status" => "None", "status" => "Active", "marriage_date" => null, "divorce_date" => null, "created_at" => "2024-10-24 11:19:04", "updated_at" => "2024-10-24 11:19:04", "deleted_at" => null],
            ["id" => 7, "man_id" => 17, "woman_id" => 14, "creator_id" => 1, "editor_id" => null, "marriage_status" => "None", "status" => "Active", "marriage_date" => null, "divorce_date" => null, "created_at" => "2024-10-24 11:19:18", "updated_at" => "2024-10-24 11:19:18", "deleted_at" => null],
            ["id" => 8, "man_id" => 19, "woman_id" => 18, "creator_id" => 1, "editor_id" => null, "marriage_status" => "None", "status" => "Active", "marriage_date" => null, "divorce_date" => null, "created_at" => null, "updated_at" => null, "deleted_at" => null],
            ["id" => 9, "man_id" => 15, "woman_id" => 29, "creator_id" => 1, "editor_id" => null, "marriage_status" => "None", "status" => "Active", "marriage_date" => null, "divorce_date" => null, "created_at" => null, "updated_at" => null, "deleted_at" => null],
            ["id" => 10, "man_id" => 15, "woman_id" => 35, "creator_id" => 1, "editor_id" => null, "marriage_status" => "None", "status" => "Active", "marriage_date" => null, "divorce_date" => null, "created_at" => null, "updated_at" => null, "deleted_at" => null],
            ["id" => 11, "man_id" => 13, "woman_id" => 40, "creator_id" => 1, "editor_id" => null, "marriage_status" => "None", "status" => "Active", "marriage_date" => null, "divorce_date" => null, "created_at" => null, "updated_at" => null, "deleted_at" => null],
            ["id" => 12, "man_id" => 42, "woman_id" => 43, "creator_id" => 1, "editor_id" => null, "marriage_status" => "None", "status" => "Active", "marriage_date" => null, "divorce_date" => null, "created_at" => null, "updated_at" => null, "deleted_at" => null]
        ];


        foreach ($data as $marriage) {
            PersonMarriage::create($marriage);
        }
    }
}
