<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\VolumeExtra;

class VolumeExtraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $data = [
            [
                "title" => "2GB",
                "day_number" => 365,
                'created_at' => now(),
            ],
            [
                "title" => "4GB",
                "day_number" => 365,
                'created_at' => now(),
            ]
        ];

        VolumeExtra::insert($data);
    }
}
