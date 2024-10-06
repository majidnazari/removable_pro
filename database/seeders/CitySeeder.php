<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\City;


class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $data = [
            [
                "province_id" => 2,
                "title" => "سبزوار",
                "code" => "SBZ",
                'created_at' => now(),
            ],
            [
                "province_id" => 2,
                "title" => "مشهد",
                "code" => "MSH",
                'created_at' => now(),
            ],
            [
                "province_id" => 2,
                "title" => "نیشابور",
                "code" => "NSH",
                'created_at' => now(),
            ],
            [
                "province_id" => 2,
                "title" => "قوچان",
                "code" => "QUCH",
                'created_at' => now(),
            ],
        ];

        City::insert($data);
    }
}
