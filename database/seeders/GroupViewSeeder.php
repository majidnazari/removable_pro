<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GroupView;

class GroupViewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $data = [
            [
                "title" => "همه",
                "status" => 1,
                "created_at" => now()->format("Y-m-d H:i:s")
            ],
            [
                "title" => "عمومی",
                "status" => 1,
                "created_at" => now()->format("Y-m-d H:i:s")
            ],
            [
                "title" => "خصوصی",
                "status" => 1,
                "created_at" => now()->format("Y-m-d H:i:s")
            ],
            [
                "title" => "خاندان خودم",
                "status" => 1,
                "created_at" => now()->format("Y-m-d H:i:s")
            ],
            [
                "title" => "خانواده خودم",
                "status" => 1,
                "created_at" => now()->format("Y-m-d H:i:s")
            ],
            [
                "title" => " فقط برادران ",
                "status" => 1,
                "created_at" => now()->format("Y-m-d H:i:s")
            ],
        ];

        GroupView::insert($data);
    }
}
