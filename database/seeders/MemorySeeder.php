<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Memory;

class MemorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $data = [
        //     [
        //         "person_id" => 1,
        //         "category_content_id" => 1, // it is voice
        //         "group_category_id" => 1, // it is to show all
        //         "creator_id" => 1,
        //         "editor_id" => null,
        //         "content" => "voice1.mp3",
        //         "title" => " و صیت نامه",
        //         "description" => "هرکس به این وصیت نامه بسیار مهم عمل نکند از وراثت بنده خارج می شود.",
        //         "is_shown_after_death" => true,
        //         "status" => 1,
        //         "created_at" => now()->format("Y-m-d H:i:s")
        //     ],
        //     [
        //         "person_id" => 1,
        //         "category_content_id" => 2, // it is  video
        //         "group_category_id" => 1, // it is to show all
        //         "creator_id" => 1,
        //         "editor_id" => null,
        //         "content" => "video1.flv",
        //         "title" => " وصیت نامه تصویری ",
        //         "description" => "هرکس به این وصیت نامه تصویری بسیار مهم عمل نکند از وراثت بنده خارج می شود.",
        //         "is_shown_after_death" => true,
        //         "status" => 1,
        //         "created_at" => now()->format("Y-m-d H:i:s")
        //     ],
        //     [
        //         "person_id" => 1,
        //         "category_content_id" => 3, // it is  video
        //         "group_category_id" => 1, // it is to show all
        //         "creator_id" => 1,
        //         "editor_id" => null,
        //         "content" => "text1.txt",
        //         "title" => " وصیت نامه متنی ",
        //         "description" => "هرکس به این وصیت نامه متنی بسیار مهم عمل نکند از وراثت بنده خارج می شود.",
        //         "is_shown_after_death" => true,
        //         "status" => 1,
        //         "created_at" => now()->format("Y-m-d H:i:s")
        //     ],

        // ];

        // Memory::insert($data);
    }
}
