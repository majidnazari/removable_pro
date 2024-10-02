<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Favorite;

class FavoriteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $data = [
            [
                "creator_id" => 1,
                "person_id" => 1,
                "image" => "1.jpg",
                "title" => "ipad",
                "description" => " i love iphone 18",
                "star" => "5",
                "status" => "Active",
                "created_at" => now()->format("Y-m-d H:i:s")
            ],
            [
                "creator_id" => 1,
                "person_id" => 1,
                "image" => "2.jpg",
                "title" => "iphone",
                "description" => " i love iphone 18+",
                "star" => "5",
                "status" => "Active",
                "created_at" => now()->format("Y-m-d H:i:s")
            ],
            [
                "creator_id" => 1,
                "person_id" => 1,
                "image" => "3.jpg",
                "title" => "masla",
                "description" => " i love traveling to north of iran",
                "star" => "5",
                "status" => "Active",
                "created_at" => now()->format("Y-m-d H:i:s")
            ],
            [
                "creator_id" => 1,
                "person_id" => 1,
                "image" => "4.jpg",
                "title" => "exercise",
                "description" => "running in the morning",
                "star" => "5",
                "status" => "Active",
                "created_at" => now()->format("Y-m-d H:i:s")
            ],
            [
                "creator_id" => 1,
                "person_id" => 1,
                "image" => "5.jpg",
                "title" => "coffee",
                "description" => " i love double spreso(dark sps)",
                "star" => "5",
                "status" => "Active",
                "created_at" => now()->format("Y-m-d H:i:s")
            ],
        ];

        Favorite::insert($data);
    }
}
