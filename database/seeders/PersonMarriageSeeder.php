<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\models\PersonMarriage;

class PersonMarriageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $data=[
            [
                'creator_id' => 1,
                'man_id' => 1,
                'woman_id' => 2,
                'marriage_status' => 'Related',
                // 'spouse_status' => 'Alive',
                'status' => 'Active',
                'marrage_date' => '2015-06-15 00:00:00',
                'divorce_date' => null,
                'created_at' => now(),                
            ],
            [
                'creator_id' => 1,
                'man_id' => 5,
                'woman_id' => 6,
                'marriage_status' => 'Related',
                // 'spouse_status' => 'Alive',
                'status' => 'Active',
                'marrage_date' => '1970-10-22 00:00:00',
                'divorce_date' => null,
                'created_at' => now(),               
            ],
            // [
            //     'man_id' => 5,
            //     'woman_id' => 6,
            //     'marriage_status' => 'Related',
            //     'spouse_status' => 'divorce',
            //     'status' => 'InActive',
            //     'marrage_date' => '2010-01-10 00:00:00',
            //     'divorce_date' => '2020-01-10 00:00:00',
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
            // [
            //     'man_id' => 7,
            //     'woman_id' => 8,
            //     'marriage_status' => 'Suspend',
            //     'spouse_status' => 'Unkown',
            //     'status' => 'Active',
            //     'marrage_date' => '2005-08-05 00:00:00',
            //     'divorce_date' => null,
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
        ];

        PersonMarriage::Insert($data);
    }
}
