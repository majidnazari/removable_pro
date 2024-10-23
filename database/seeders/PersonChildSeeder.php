<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PersonChild;

class PersonChildSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $data=
        [
            
            [
                'creator_id' => 1,
                'editor_id' => null,
                'person_marriage_id' => 1,
                'child_id' => 3,
                'child_kind' => "Direct_child",
                'child_status' => "With_family",
                
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'creator_id' => 1,
                'editor_id' => null,
                'person_marriage_id' => 1,
                'child_id' => 4,
                'child_kind' => "Direct_child",
                'child_status' => "With_family",
                
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        PersonChild::insert($data);
    }
}
