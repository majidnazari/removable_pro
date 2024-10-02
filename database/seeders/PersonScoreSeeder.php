<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\PersonScore;

class PersonScoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data=
        [
            
            [
                'creator_id' => 1,
                'editor_id' => null,
                'person_id' => 1,
                'score_id' => 1,
                'score_level' => "Excellent",               
                'status' => 'Active',
                'created_at' => now(),
            ],
            [
                'creator_id' => 1,
                'editor_id' => null,
                'person_id' => 1,
                'score_id' => 2,
                'score_level' => "good",               
                'status' => 'Active',
                'created_at' => now(),
            ],
            [
                'creator_id' => 1,
                'editor_id' => null,
                'person_id' => 1,
                'score_id' => 3,
                'score_level' => "NotBad",               
                'status' => 'Active',
                'created_at' => now(),
            ],
        ];

        PersonScore::insert($data);
    }
}
