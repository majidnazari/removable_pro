<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\models\PersonSubscription;


class PersonSubscriptionSeeder extends Seeder
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
                'person_id' => 1,
                'subscription_id' => 1,
                'start_date' => now()->format('Y-m-d H:i:s'),
                'end_date' => now()->format('Y-m-d H:i:s'),
                'status' => 'Active',               
                'created_at' => now(),
            ],
            [
                'creator_id' => 1,
                'person_id' => 1,
                'subscription_id' => 1,
                'start_date' => now()->addMonths(-6)->format('Y-m-d H:i:s'),
                'end_date' => now()->addMonths(-3)->format('Y-m-d H:i:s'),
                'status' => 'Active',               
                'created_at' => now(),
            ],
            
        ];

        PersonSubscription::Insert($data);
    }
}
