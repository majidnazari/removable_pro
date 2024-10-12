<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\UserMobile;

class UserMobileSeeder extends Seeder
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
                "mobile" => "09375371610",   
                         
                'created_at' => now(),
            ],
           
        ];

        UserMobile::insert($data);
    }
}
