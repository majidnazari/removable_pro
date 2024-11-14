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
                "title" => "Father",
                "priority" => 1,
                "status" => 1,   
                         
                'created_at' => now(),
            ],
            [
                "title" => "Mother",
                "priority" => 1,
                "status" => 1,   
                         
                'created_at' => now(),
            ],
            [
                "title" => "Spouse",
                "priority" => 1,
                "status" => 1,   
                         
                'created_at' => now(),
            ],
            [
                "title" => "Son",
                "priority" => 1,
                "status" => 1,   
                         
                'created_at' => now(),
            ],
            [
                "title" => "Daughter",
                "priority" => 1,
                "status" => 1,   
                         
                'created_at' => now(),
            ],
           
        ];

        NaslanRelationship::insert($data);
    }
}
