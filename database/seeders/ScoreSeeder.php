<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Score;

class ScoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'title' => "محبوبیت رفتاری",
                'status' => 1,
                'created_at' => now(),
            ],
            [
                'title' => "محبوبیت علمی",
                'status' => 1,
                'created_at' => now(),
            ],
            [
                'title' => "محبوبیت مالی",
                'status' => 1,
                'created_at' => now(),
            ],

        ];

        Score::Insert($data);
    }
}
