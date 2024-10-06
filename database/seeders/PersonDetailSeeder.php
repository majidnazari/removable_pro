<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\PersonDetail;

class PersonDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $data=[
                [
                    "person_id" => 1,
                    "profile_picture" =>"pic1.jpg",
                    "gendar" => "Male",
                    "physical_condition" => "Healthy",
                    "created_at" =>\Carbon\Carbon::now()->format("Y-m-d H:i:s"),
                ],
                [
                    "person_id" => 1,
                    "profile_picture" =>"pic1.jpg",
                    "gendar" => "Male",
                    "physical_condition" => "Healthy",
                    "created_at" =>\Carbon\Carbon::now()->format("Y-m-d H:i:s"),
                ]
            ];
            PersonDetail::insert($data);
    }
}
