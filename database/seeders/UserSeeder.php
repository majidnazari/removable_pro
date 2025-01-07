<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use Log;

class UserSeeder extends Seeder
{
    public function run()
    {
       // Load and decode JSON data
        $jsonPath = database_path('datasample/user.json'); // Replace with the actual path to your JSON file
       
            // Load and decode the JSON data
            $jsonData = json_decode(File::get($jsonPath), true);

            foreach ($jsonData as $userData) {
                User::updateOrCreate(
                    ['id' => $userData['id']], // Unique identifier to check if the user exists
                    [
                        'country_code' => $userData['country_code'] ?? null,
                        'mobile' => $userData['mobile'] ?? null,
                        'email' => $userData['email'] ?? null,
                        'email_verified_at' => $userData['email_verified_at'] ?? null,
                        'mobile_is_verified' => $userData['mobile_is_verified'] ?? false,
                        'password' => $userData['password'] ?? null,
                        'sent_code' => $userData['sent_code'] ?? null,
                        'code_expired_at' => $userData['code_expired_at'] ?? null,
                        'password_change_attempts' => $userData['password_change_attempts'] ?? 0,
                        'last_password_change_attempt' => $userData['last_password_change_attempt'] ?? null,
                        'last_attempt_at' => $userData['last_attempt_at'] ?? null,
                        'status' => $userData['status'] ?? 1,
                        'clan_id' => $userData['clan_id'] ,
                        'remember_token' => $userData['remember_token'] ?? null,
                        'created_at' => $userData['created_at'] ?? now(),
                        'updated_at' => $userData['updated_at'] ?? now(),
                        'deleted_at' => $userData['deleted_at'] ?? null,
                        'avatar' => $userData['avatar'] ?? null,
                    ]
                );
            }
            // public function run()
    // {
    //     $data = [
    //         [
    //             "country_code" => "098",
    //             "mobile" => "9372120890",
    //             "mobile_is_verified" => true,
    //             "email" => "majidnazarister@gmail.com",
    //             "password" => Hash::make("12345678@"),
    //             "status" => "Active",
    //             "sent_code" => null,
    //             "code_expired_at" => null,
    //             //"user_attempt_time" => 0,
                
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             "country_code" => "098",
    //             "mobile" => "9372120892",
    //             "mobile_is_verified" => true,
    //             "email" => "majidnazarister2@gmail.com",
    //             "password" => Hash::make("12345678@"),
    //             "status" => "Active",
    //             "sent_code" => "4532",
    //             "code_expired_at" => Carbon::now()->addMinutes(2),
    //             //"user_attempt_time" => 1,
              
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             "country_code" => "098",
    //             "mobile" => "9372120893",
    //             "mobile_is_verified" => true,
    //             "email" => "majidnazarister3@gmail.com",
    //             "password" => Hash::make("12345678@"),
    //             "status" => "Active",
    //             "sent_code" => "8765",
    //             "code_expired_at" => Carbon::now()->addMinutes(2),
    //            // "user_attempt_time" => 3,
                
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             "country_code" => "098",
    //             "mobile" => "9372120894",
    //             "mobile_is_verified" => true,
    //             "email" => "majidnazarister4@gmail.com",
    //             "password" => Hash::make("12345678@"),
    //             "status" => "Active",
    //             "sent_code" => null,
    //             "code_expired_at" => null,
    //             //"user_attempt_time" => 0,
               
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //     ];

    //     \App\Models\User::insert($data);
     }
}

