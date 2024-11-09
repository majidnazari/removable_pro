<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                "country_code" => "098",
                "mobile" => "9372120890",
                "mobile_is_verified" => true,
                "email" => "majidnazarister@gmail.com",
                "password" => Hash::make("12345678@"),
                "status" => "Active",
                "sent_code" => null,
                "code_expired_at" => null,
                //"user_attempt_time" => 0,
                
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "country_code" => "098",
                "mobile" => "9372120892",
                "mobile_is_verified" => true,
                "email" => "majidnazarister2@gmail.com",
                "password" => Hash::make("12345678@"),
                "status" => "Active",
                "sent_code" => "4532",
                "code_expired_at" => Carbon::now()->addMinutes(2),
                //"user_attempt_time" => 1,
              
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "country_code" => "098",
                "mobile" => "9372120893",
                "mobile_is_verified" => true,
                "email" => "majidnazarister3@gmail.com",
                "password" => Hash::make("12345678@"),
                "status" => "Active",
                "sent_code" => "8765",
                "code_expired_at" => Carbon::now()->addMinutes(2),
               // "user_attempt_time" => 3,
                
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "country_code" => "098",
                "mobile" => "9372120894",
                "mobile_is_verified" => true,
                "email" => "majidnazarister4@gmail.com",
                "password" => Hash::make("12345678@"),
                "status" => "Active",
                "sent_code" => null,
                "code_expired_at" => null,
                //"user_attempt_time" => 0,
               
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        \App\Models\User::insert($data);
    }
}

