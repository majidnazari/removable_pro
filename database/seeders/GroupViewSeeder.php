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
                "status" => "Active",
                "created_at" => now()->format("Y-m-d H:i:s")
            ],
            [
                "title" => "عمومی",
                "status" => "Active",
                "created_at" => now()->format("Y-m-d H:i:s")
            ],
            [
                "title" => "خصوصی",
                "status" => "Active",
                "created_at" => now()->format("Y-m-d H:i:s")
            ],
            [
                "title" => "خاندان خودم",
                "status" => "Active",
                "created_at" => now()->format("Y-m-d H:i:s")
            ],
            [
                "title" => "خانواده خودم",
                "status" => "Active",
                "created_at" => now()->format("Y-m-d H:i:s")
            ],
            [
                "title" => " فقط برادران ",
                "status" => "Active",
                "created_at" => now()->format("Y-m-d H:i:s")
            ],
        ];

        GroupView::insert($data);
    }
}
