<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $data = [
            [
                "country_id" => 1,
                "title" => "تهران",
                "code" => "THR",
                'created_at' => now(),
            ],
            [
                "country_id" => 1,
                "title" => "مشهد",
                "code" => "MSH",
                'created_at' => now(),
            ],
            [
                "country_id" => 1,
                "title" => "تبریز",
                "code" => "TBR",
                'created_at' => now(),
            ],
            [
                "country_id" => 1,
                "title" => "اصفهان",
                "code" => "ISF",
                'created_at' => now(),
            ],
        ];

        Province::insert($data);
    }
}
