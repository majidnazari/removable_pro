<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Person;

class PersonSeeder extends Seeder
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
                'node_code' => 'N001',
                'node_level_x' => 1,
                'node_level_y' => 1,
                'first_name' => 'مجید',
                'last_name' => 'نظری',
                'birth_date' => '1990-01-01 00:00:00',
                //'death_date' => null,
                'is_owner' => 1,
                'status' => 'Active',
                'created_at' => now(),
                // 'node_level' => 2,
                // 'naslan_id' => 'NAS001',
                // 'referal_code' => 'REF001',
                
                //'mobile' => '09372120890',
               
                //'family_title' => 'نظری',
                //'father_first_name' => 'محمد تقی',
                //'mother_first_name' => 'فریده',
                //'mother_last_name' => 'رجبی',
                // 'birth_location' => 'کرج',
                // 'address' => 'قاسم آباد',
                // 'image' => 'image1.jpg',
                // 'lat' => 40.71,
                // 'lon' => -74.00,
                // 'gendar' => 'Male',
                // 'physical_condition' => 'Healthy',
               
            ]
            ,
            [
                'creator_id' => 1,
                'node_code' => 'N002',
                'node_level_x' => 1,
                'node_level_y' => 1,
                'first_name' => 'فاطمه',
                'last_name' => 'رحمتی',
                'birth_date' => '1985-05-05 00:00:00',
                //'death_date' => null,
                //'mobile' => '09375371610',
                'is_owner' => 0,
                'status' => 'Active',
                'created_at' => now(),
                //'family_title' => 'رحمتی',
                //'father_first_name' => 'صدیقه',
                //'mother_first_name' => 'شمس',
                //'mother_last_name' => 'آقایی',
                //'birth_location' => 'مشهد',
                //'address' => 'کوهسنگی',
                //'image' => 'image2.jpg',
                //'lat' => 34.05,
                //'lon' => -118.24,
                //'gendar' => 'Female',
                //'physical_condition' => 'Handicapped',
                
            ],
            [
                'creator_id' => 1,
                'node_code' => 'N003',
                'node_level_x' => 2,
                'node_level_y' => 1,
                'first_name' => 'افشین',
                'last_name' => 'نظری',
                'birth_date' => '2015-10-10 00:00:00',
                //'death_date' => null,
                'is_owner' => 0,
                'status' => 'Active',
                'created_at' => now(),
                //'mobile' => '',
                //'family_title' => 'نظری',
                //'father_first_name' => 'مجید',
                //'mother_first_name' => 'فاطمه',
                //'mother_last_name' => 'رحمتی',
                //'birth_location' => 'مشهد',
                //'address' => 'مشهد قاسم آباد',
                //'image' => 'image3.jpg',
                //'lat' => 41.87,
                //'lon' => -87.62,
                //gendar' => 'Male',
                //'physical_condition' => 'Healthy',
                
            ],
            [
                'creator_id' => 1,
                'node_code' => 'N004',
                'node_level_x' => 1,
                'node_level_y' => 1,
                'first_name' => 'سوسن',
                'last_name' => 'نظری',
                'birth_date' => '2018-11-10 00:00:00',
                //'death_date' => null,
                'is_owner' => 0,
                'status' => 'Active',
                'created_at' => now(),
                //'mobile' => '',
                //'family_title' => 'نظری',
                //'father_first_name' => 'مجید',
                //'mother_first_name' => 'فاطمه',
                //'mother_last_name' => 'رحمتی',
                //'birth_location' => 'مشهد',
                //'address' => 'مشهد فاسم آیاد',
                //'image' => 'image4.jpg',
                //'lat' => 41.87,
                //'lon' => -87.62,
                //'gendar' => 'Female',
                //'physical_condition' => 'Healthy',
                
            ],
            [
                'creator_id' => 1,
                'node_code' => 'N005',
                'node_level_x' => 1,
                'node_level_y' => 1,
                'first_name' => 'محمد تقی',
                'last_name' => 'نظری',
                'birth_date' => '1902-10-10 00:00:00',
                //'death_date' => null,
                'is_owner' => 0,
                'status' => 'Active',
                'created_at' => now(),
                //'mobile' => '',
               // 'family_title' => 'نظری',
               // 'father_first_name' => 'سیف اله',
                //'mother_first_name' => 'عزیز',
                //'mother_last_name' => 'اکبری',
                //'birth_location' => 'گیلان',
               // 'address' => 'گیلان',
               // 'image' => 'image5.jpg',
                //'lat' => 41.87,
                //'lon' => -87.62,
                //'gendar' => 'Male',
                //'physical_condition' => 'Healthy',
                
            ],
            [
                'creator_id' => 1,
                'node_code' => 'N006',
                'node_level_x' => 1,
                'node_level_y' => 1,
                'first_name' => 'فریده',
                'last_name' => 'رجبی',
                'birth_date' => '1950-11-10 00:00:00',
                //'death_date' => null,
                'is_owner' => 0,
                'status' => 'Active',
                'created_at' => now(),
                //'mobile' => '',
                //'family_title' => 'رجبی',
                //'father_first_name' => 'قاسم',
                //'mother_first_name' => 'مریم',
                //'mother_last_name' => '',
                //'birth_location' => 'گیلان',
                //'address' => 'لاهیجان',
                //'image' => 'image6.jpg',
                //'lat' => 41.87,
                //'lon' => -87.62,
                //'gendar' => 'Female',
                //'physical_condition' => 'Healthy',
               
            ]
        ];

        Person::insert($data);
    }
}
