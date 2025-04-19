<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FamilyEvent;

class FamilyEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $data = [
            [
                "person_id" => 1,
                "event_id" => 1,
                "creator_id" => 1,
                "editor_id" => null,
                "category_content_id" => 4,

                "event_date" => now()->format("Y-m-d H:i:s"),
                "status" => 1,
                "created_at" => now()->format("Y-m-d H:i:s"),
            ],
            [
                "person_id" => 1,
                "event_id" => 2,
                "creator_id" => 1,
                "editor_id" => null,
                "category_content_id" => 4,
                "event_date" => now()->format("Y-m-d H:i:s"),
                "status" => 1,
                "created_at" => now()->format("Y-m-d H:i:s"),
            ],
            [
                "person_id" => 1,
                "event_id" => 3,
                "creator_id" => 1,
                "editor_id" => null,
                "category_content_id" => 4,
                "event_date" => now()->format("Y-m-d H:i:s"),
                "status" => 1,
                "created_at" => now()->format("Y-m-d H:i:s"),
            ],
            [
                "person_id" => 1,
                "event_id" => 4,
                "creator_id" => 1,
                "editor_id" => null,
                "category_content_id" => 4,
                "event_date" => now()->format("Y-m-d H:i:s"),
                "status" => 1,
                "created_at" => now()->format("Y-m-d H:i:s"),
            ],
        ];
        FamilyEvent::insert($data);
    }
}
