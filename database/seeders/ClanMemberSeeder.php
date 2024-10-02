<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ClanMember;

class ClanMemberSeeder extends Seeder
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
                "clan_id" => 1,
                "family_code" => "FA001",                
                'created_at' => now(),
            ],
            [
                "creator_id" => 1,
                "clan_id" => 1,
                "family_code" => "FA002",                
                'created_at' => now(),
            ],
        ];

        ClanMember::insert($data);
    }
}
