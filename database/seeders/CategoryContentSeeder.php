<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CategoryContent;

class CategoryContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data=[
            [
                "title" => "صدا",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "title" => "ویدئو",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "title" => "عکس",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "title" => "دل نوشته",
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        CategoryContent::insert($data);

    }

}
