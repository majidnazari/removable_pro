<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;


class EventSeeder extends Seeder
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
                "title" => "تولد",
                "status" => 1,
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                "creator_id" => 1,
                "title" => "وفات",
                "status" => 1,
                'created_at' => now(),
                'updated_at' => now(),

            ],

        ];

        Event::insert($data);
    }
}
