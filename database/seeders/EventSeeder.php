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

        $data=[
            [
                "title" => "دوره",
                "status" =>1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "title" => "ازدواج",
                "status" =>1,
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                "title" => "فوت",
                "status" => 1,
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                "title" => "تولد",
                "status" => 1,
                'created_at' => now(),
                'updated_at' => now(),

            ],
        ];

        Event::insert($data);
    }
}
