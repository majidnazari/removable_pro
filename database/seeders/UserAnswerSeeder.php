<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\UserAnswer;

class UserAnswerSeeder extends Seeder
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
                "user_id" => 1,
                "question_id" => 1,   
                "answer" =>   'خانم شمس',          
                'created_at' => now(),
            ],
           
        ];

        UserAnswer::insert($data);
    }
}
