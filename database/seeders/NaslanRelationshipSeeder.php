<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\NaslanRelationship;

class NaslanRelationshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $data = [
            [
                "title" => "پدر",
                "priority" => 1,
                "status" => "Active",   
                         
                'created_at' => now(),
            ],
            [
                "title" => "مادر",
                "priority" => 1,
                "status" => "Active",   
                         
                'created_at' => now(),
            ],
            [
                "title" => "همسر",
                "priority" => 1,
                "status" => "Active",   
                         
                'created_at' => now(),
            ],
            [
                "title" => "پسر",
                "priority" => 1,
                "status" => "Active",   
                         
                'created_at' => now(),
            ],
            [
                "title" => "دختر",
                "priority" => 1,
                "status" => "Active",   
                         
                'created_at' => now(),
            ],
           
        ];

        NaslanRelationship::insert($data);
    }
}
