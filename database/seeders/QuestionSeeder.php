<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Question;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $data = [
            [
                "title" => 'معلم اول دبستان',
                "description" => '',
                "status" => 1,                
                'created_at' => now(),
            ],
            [
                "title" => 'نام پدر بزرگ پدری',
                "description" => '',
                "status" => 1,                
                'created_at' => now(),
            ],
        ];

        Question::insert($data);
    }
}
