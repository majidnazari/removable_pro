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
                "status" => "Active",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "title" => "ازدواج",
                "status" => "Active",
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                "title" => "فوت",
                "status" => "Active",
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                "title" => "تولد",
                "status" => "Active",
                'created_at' => now(),
                'updated_at' => now(),

            ],
        ];

        Event::insert($data);
    }
}
