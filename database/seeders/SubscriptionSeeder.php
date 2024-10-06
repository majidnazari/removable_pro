<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subscription;


class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $data=[
            [
                "title" =>  "دوره اشتراک ۱ ماهه",
                "day_number" => 30,
                "volume_amount" => 300,
                "description" => "   تستی دوره اشتراک ۱ ماهه",
                "status" => "Active",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "title" =>  "دوره اشتراک ۱۲ ماهه",
                "day_number" => 30,
                "volume_amount" => 600,
                "description" => "   تستی دوره اشتراک12 ۱ ماهه",
                "status" => "Active",
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                "title" =>  "دوره اشتراک 3 ماهه",
                "day_number" => 90,
                "volume_amount" => 3000,
                "description" => "   تستی دوره اشتراک3 ۱ ماهه",
                "status" => "Active",
                'created_at' => now(),
                'updated_at' => now(),


            ],
            [
                "title" =>  "دوره اشتراک 6 ماهه",
                "day_number" => 180,
                "volume_amount" => 30000,
                "description" => "   تستی دوره اشتراک6 ۱ ماهه",
                "status" => "Active",
                'created_at' => now(),
                'updated_at' => now(),


            ],
        ];

        Subscription::insert($data);
    }
}
