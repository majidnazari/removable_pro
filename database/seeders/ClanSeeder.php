<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Clan;

class ClanSeeder extends Seeder
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
                "title" => "نظری",
                "Clan_exact_family_name" => "نظری نژاد جوریابی",
                "Clan_code" => "CL001",
                'created_at' => now(),
            ]
        ];

        Clan::insert($data);
    }
}
