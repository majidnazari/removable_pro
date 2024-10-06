<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

Use App\Models\UserVolumeExtra;

class UserVolumeExtraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $data=[
            [
                "user_id" =>1,
                "volume_extra_id" =>1,
                "remain_volume" => 2000,
                "start_date"=> now(),
                "end_date"=> Carbon::now()->addYear()->format("Y-m-d H:i:s"),
            ]
            ];
            UserVolumeExtra::insert($data);
    }
}
