<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\FamilyBoard;

class FamilyBoardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $data = [
            [
                "creator_id"=>1,
                "category_content_id" => 1,
                "title" => "سالگرد ازدواج محمد و زهرا",
                "file_path"=> "voice1.mp3",
                "selected_date" => Carbon::now()->format("Y-m-d"),
                "description" => "it is the best event that happend",
                "status" =>1,
                'created_at' => now(),
            ],
            [
                "creator_id"=>1,
                "category_content_id" => 1,
                "title" => " شب یلدا",
                "file_path"=> "voice۲.mp3",
                "selected_date" => Carbon::now()->addMonths(3)->format("Y-m-d"),
                "description" => "it longest night during in the year",
                "status" =>1,
                'created_at' => now(),
            ],
        ];

        FamilyBoard::insert($data);

    }
}
