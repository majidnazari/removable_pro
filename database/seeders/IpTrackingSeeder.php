<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\IpTracking;
use Carbon\Carbon;

class IpTrackingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $data=[
            "ip" => "127.0.0.1",
            "date_attemp" => Carbon::now()->format("Y-m-d"),
            "total_attemp" => 1,
            "today_attemp" => 1,
        ];

        IpTracking::insert($data);
    }
}
