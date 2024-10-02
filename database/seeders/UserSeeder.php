<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelUsers;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $data=[
            [
                "creator_id" =>1,
                "editor_id" =>null,
                "user_name" => "majid_nazari@",
                "email" => "majidnazarister@gmail.com",
                "password" => Hash::make("12345678@"),
                "status" => "Active",
                'created_at' => now(),
                'updated_at' => now(),
            ],
           
        ];

        User::insert($data);
    }
}
