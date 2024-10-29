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
    // public function run(): void
    // {
    //     //
    //     $data=
    //     [
            
    //         [
    //             'creator_id' => 1,
    //             'node_code' => 'N001',
    //             'node_level_x' => 1,
    //             'node_level_y' => 1,
    //             'first_name' => 'مجید',
    //             'last_name' => 'نظری',
    //             'birth_date' => '1990-01-01 00:00:00',
    //             //'death_date' => null,
    //             'is_owner' => 1,
    //             "gendar" => 1,

    //             'status' => 'Active',
    //             'created_at' => now(),
    //             // 'node_level' => 2,
    //             // 'naslan_id' => 'NAS001',
    //             // 'referal_code' => 'REF001',
                
    //             //'mobile' => '09372120890',
               
    //             //'family_title' => 'نظری',
    //             //'father_first_name' => 'محمد تقی',
    //             //'mother_first_name' => 'فریده',
    //             //'mother_last_name' => 'رجبی',
    //             // 'birth_location' => 'کرج',
    //             // 'address' => 'قاسم آباد',
    //             // 'image' => 'image1.jpg',
    //             // 'lat' => 40.71,
    //             // 'lon' => -74.00,
    //             // 'gendar' => 'Male',
    //             // 'physical_condition' => 'Healthy',
               
    //         ]
    //         ,
    //         [
    //             'creator_id' => 1,
    //             'node_code' => 'N002',
    //             'node_level_x' => 1,
    //             'node_level_y' => 1,
    //             'first_name' => 'فاطمه',
    //             'last_name' => 'رحمتی',
    //             'birth_date' => '1985-05-05 00:00:00',
    //             //'death_date' => null,
    //             //'mobile' => '09375371610',
    //             'is_owner' => 0,
    //             "gendar" => 0,

    //             'status' => 'Active',
    //             'created_at' => now(),
    //             //'family_title' => 'رحمتی',
    //             //'father_first_name' => 'صدیقه',
    //             //'mother_first_name' => 'شمس',
    //             //'mother_last_name' => 'آقایی',
    //             //'birth_location' => 'مشهد',
    //             //'address' => 'کوهسنگی',
    //             //'image' => 'image2.jpg',
    //             //'lat' => 34.05,
    //             //'lon' => -118.24,
    //             //'gendar' => 'Female',
    //             //'physical_condition' => 'Handicapped',
                
    //         ],
    //         [
    //             'creator_id' => 1,
    //             'node_code' => 'N003',
    //             'node_level_x' => 2,
    //             'node_level_y' => 1,
    //             'first_name' => 'افشین',
    //             'last_name' => 'نظری',
    //             'birth_date' => '2015-10-10 00:00:00',
    //             //'death_date' => null,
    //             'is_owner' => 0,
    //             "gendar" => 1,

    //             'status' => 'Active',
    //             'created_at' => now(),
    //             //'mobile' => '',
    //             //'family_title' => 'نظری',
    //             //'father_first_name' => 'مجید',
    //             //'mother_first_name' => 'فاطمه',
    //             //'mother_last_name' => 'رحمتی',
    //             //'birth_location' => 'مشهد',
    //             //'address' => 'مشهد قاسم آباد',
    //             //'image' => 'image3.jpg',
    //             //'lat' => 41.87,
    //             //'lon' => -87.62,
    //             //gendar' => 'Male',
    //             //'physical_condition' => 'Healthy',
                
    //         ],
    //         [
    //             'creator_id' => 1,
    //             'node_code' => 'N004',
    //             'node_level_x' => 1,
    //             'node_level_y' => 1,
    //             'first_name' => 'سوسن',
    //             'last_name' => 'نظری',
    //             'birth_date' => '2018-11-10 00:00:00',
    //             //'death_date' => null,
    //             'is_owner' => 0,
    //             "gendar" => 0,

    //             'status' => 'Active',
    //             'created_at' => now(),
    //             //'mobile' => '',
    //             //'family_title' => 'نظری',
    //             //'father_first_name' => 'مجید',
    //             //'mother_first_name' => 'فاطمه',
    //             //'mother_last_name' => 'رحمتی',
    //             //'birth_location' => 'مشهد',
    //             //'address' => 'مشهد فاسم آیاد',
    //             //'image' => 'image4.jpg',
    //             //'lat' => 41.87,
    //             //'lon' => -87.62,
    //             //'gendar' => 'Female',
    //             //'physical_condition' => 'Healthy',
                
    //         ],
    //         [
    //             'creator_id' => 1,
    //             'node_code' => 'N005',
    //             'node_level_x' => 1,
    //             'node_level_y' => 1,
    //             'first_name' => 'محمد تقی',
    //             'last_name' => 'نظری',
    //             'birth_date' => '1902-10-10 00:00:00',
    //             //'death_date' => null,
    //             'is_owner' => 0,
    //             "gendar" => 1,

    //             'status' => 'Active',
    //             'created_at' => now(),
    //             //'mobile' => '',
    //            // 'family_title' => 'نظری',
    //            // 'father_first_name' => 'سیف اله',
    //             //'mother_first_name' => 'عزیز',
    //             //'mother_last_name' => 'اکبری',
    //             //'birth_location' => 'گیلان',
    //            // 'address' => 'گیلان',
    //            // 'image' => 'image5.jpg',
    //             //'lat' => 41.87,
    //             //'lon' => -87.62,
    //             //'gendar' => 'Male',
    //             //'physical_condition' => 'Healthy',
                
    //         ],
    //         [
    //             'creator_id' => 1,
    //             'node_code' => 'N006',
    //             'node_level_x' => 1,
    //             'node_level_y' => 1,
    //             'first_name' => 'فریده',
    //             'last_name' => 'رجبی',
    //             'birth_date' => '1950-11-10 00:00:00',
    //             //'death_date' => null,
    //             'is_owner' => 0,
    //             "gendar" => 0,

    //             'status' => 'Active',
    //             'created_at' => now(),
    //             //'mobile' => '',
    //             //'family_title' => 'رجبی',
    //             //'father_first_name' => 'قاسم',
    //             //'mother_first_name' => 'مریم',
    //             //'mother_last_name' => '',
    //             //'birth_location' => 'گیلان',
    //             //'address' => 'لاهیجان',
    //             //'image' => 'image6.jpg',
    //             //'lat' => 41.87,
    //             //'lon' => -87.62,
    //             //'gendar' => 'Female',
    //             //'physical_condition' => 'Healthy',
               
    //         ]
    //     ];

    //     Person::insert($data);
    // }
    public function run()
    {
        // $data = [
        //     ["id" => 1, "creator_id" => 1, "editor_id" => null, "node_code" => "N001", "node_level_x" => 1, "node_level_y" => 1, "first_name" => "مجید", "last_name" => "نظری", "birth_date" => "1990-01-01 00:00:00", "death_date" => null, "is_owner" => 1, "gendar" => 1, "status" => "Active", "created_at" => "2024-10-23 16:16:04", "updated_at" => null, "deleted_at" => null],
        //     ["id" => 2, "creator_id" => 1, "editor_id" => null, "node_code" => "N002", "node_level_x" => 1, "node_level_y" => 1, "first_name" => "فاطمه", "last_name" => "رحمتی", "birth_date" => "1985-05-05 00:00:00", "death_date" => null, "is_owner" => 0, "gendar" => 0, "status" => "Active", "created_at" => "2024-10-23 16:16:04", "updated_at" => null, "deleted_at" => null],
        //     ["id" => 3, "creator_id" => 1, "editor_id" => null, "node_code" => "N003", "node_level_x" => 2, "node_level_y" => 1, "first_name" => "افشین", "last_name" => "نظری", "birth_date" => "2016-06-15 00:00:00", "death_date" => null, "is_owner" => 0, "gendar" => 1, "status" => "Active", "created_at" => "2024-10-23 16:16:04", "updated_at" => null, "deleted_at" => null],
        //     ["id" => 4, "creator_id" => 1, "editor_id" => null, "node_code" => "N004", "node_level_x" => 1, "node_level_y" => 1, "first_name" => "سوسن", "last_name" => "نظری", "birth_date" => "2018-11-10 00:00:00", "death_date" => null, "is_owner" => 0, "gendar" => 0, "status" => "Active", "created_at" => "2024-10-23 16:16:04", "updated_at" => null, "deleted_at" => null],
        //     ["id" => 5, "creator_id" => 1, "editor_id" => null, "node_code" => "N005", "node_level_x" => 1, "node_level_y" => 1, "first_name" => "محمد تقی", "last_name" => "نظری", "birth_date" => "1902-10-10 00:00:00", "death_date" => null, "is_owner" => 0, "gendar" => 1, "status" => "Active", "created_at" => "2024-10-23 16:16:04", "updated_at" => null, "deleted_at" => null],
        //     ["id" => 6, "creator_id" => 1, "editor_id" => null, "node_code" => "N006", "node_level_x" => 1, "node_level_y" => 1, "first_name" => "فریده", "last_name" => "رجبی", "birth_date" => "1950-11-10 00:00:00", "death_date" => null, "is_owner" => 0, "gendar" => 0, "status" => "Active", "created_at" => "2024-10-23 16:16:04", "updated_at" => null, "deleted_at" => null],
        //     ["id" => 7, "creator_id" => 1, "editor_id" => null, "node_code" => "N008", "node_level_x" => 1, "node_level_y" => 1, "first_name" => "آیلین", "last_name" => "اجتماعی", "birth_date" => "1985-05-05 00:00:00", "death_date" => null, "is_owner" => 0, "gendar" => 0, "status" => "Active", "created_at" => "2024-10-23 16:16:04", "updated_at" => null, "deleted_at" => null],
        //     ["id" => 8, "creator_id" => 1, "editor_id" => null, "node_code" => "N009", "node_level_x" => 1, "node_level_y" => 1, "first_name" => "آرش", "last_name" => "نظری", "birth_date" => "1985-05-05 00:00:00", "death_date" => null, "is_owner" => 0, "gendar" => 0, "status" => "Active", "created_at" => "2024-10-23 16:16:04", "updated_at" => null, "deleted_at" => null],
        //     ["id" => 9, "creator_id" => 1, "editor_id" => null, "node_code" => "N0031", "node_level_x" => 2, "node_level_y" => 1, "first_name" => "شهلا", "last_name" => "شیری", "birth_date" => "2015-10-10 00:00:00", "death_date" => null, "is_owner" => 0, "gendar" => 1, "status" => "Active", "created_at" => "2024-10-23 16:16:04", "updated_at" => null, "deleted_at" => null],
        //     ["id" => 10, "creator_id" => 1, "editor_id" => null, "node_code" => "N0032", "node_level_x" => 2, "node_level_y" => 1, "first_name" => "فرامرز", "last_name" => "اصلانی", "birth_date" => "2015-10-10 00:00:00", "death_date" => null, "is_owner" => 0, "gendar" => 1, "status" => "Active", "created_at" => "2024-10-23 16:16:04", "updated_at" => null, "deleted_at" => null],
        //     ["id" => 12, "creator_id" => 1, "editor_id" => null, "node_code" => "N0033", "node_level_x" => 2, "node_level_y" => 1, "first_name" => "آدیداس", "last_name" => "نظری", "birth_date" => "2015-10-10 00:00:00", "death_date" => null, "is_owner" => 0, "gendar" => 1, "status" => "Active", "created_at" => "2024-10-23 16:16:04", "updated_at" => null, "deleted_at" => null],
        //     ["id" => 13, "creator_id" => 1, "editor_id" => null, "node_code" => "N0034", "node_level_x" => 2, "node_level_y" => 1, "first_name" => "سامان", "last_name" => "اصلانی", "birth_date" => "2015-10-10 00:00:00", "death_date" => null, "is_owner" => 0, "gendar" => 1, "status" => "Active", "created_at" => "2024-10-23 16:16:04", "updated_at" => null, "deleted_at" => null],
        //     ["id" => 14, "creator_id" => 1, "editor_id" => null, "node_code" => "N0035", "node_level_x" => 2, "node_level_y" => 1, "first_name" => "قاسم", "last_name" => "رجبی", "birth_date" => "2015-10-10 00:00:00", "death_date" => null, "is_owner" => 0, "gendar" => 1, "status" => "Active", "created_at" => "2024-10-23 16:16:04", "updated_at" => null, "deleted_at" => null],
        //     ["id" => 16, "creator_id" => 1, "editor_id" => null, "node_code" => "N0036", "node_level_x" => 2, "node_level_y" => 1, "first_name" => "مریم", "last_name" => "رجبی", "birth_date" => "2015-10-10 00:00:00", "death_date" => null, "is_owner" => 0, "gendar" => 1, "status" => "Active", "created_at" => "2024-10-23 16:16:04", "updated_at" => null, "deleted_at" => null],
        //     ["id" => 17, "creator_id" => 1, "editor_id" => null, "node_code" => "N0037", "node_level_x" => 2, "node_level_y" => 1, "first_name" => "سیف اله", "last_name" => "نظری", "birth_date" => "2015-10-10 00:00:00", "death_date" => null, "is_owner" => 0, "gendar" => 1, "status" => "Active", "created_at" => "2024-10-23 16:16:04", "updated_at" => null, "deleted_at" => null],
        //     ["id" => 18, "creator_id" => 1, "editor_id" => null, "node_code" => "N0038", "node_level_x" => 2, "node_level_y" => 1, "first_name" => "خاتون", "last_name" => "جمالی", "birth_date" => "2015-10-10 00:00:00", "death_date" => null, "is_owner" => 0, "gendar" => 1, "status" => "Active", "created_at" => "2024-10-23 16:16:04", "updated_at" => null, "deleted_at" => null]
        // ];

        $data = [
            ["id" => 1, "creator_id" => 1, "editor_id" => null, "node_code" => "N001", "node_level_x" => 1, "node_level_y" => 1, "first_name" => "مجید", "last_name" => "نظری", "birth_date" => "1990-01-01 00:00:00", "death_date" => null, "is_owner" => 1, "gendar" => 1, "status" => "Active", "created_at" => "2024-10-24 10:05:08", "updated_at" => null, "deleted_at" => null],
            ["id" => 2, "creator_id" => 1, "editor_id" => null, "node_code" => "N002", "node_level_x" => 1, "node_level_y" => 1, "first_name" => "فاطمه", "last_name" => "رحمتی", "birth_date" => "1985-05-05 00:00:00", "death_date" => null, "is_owner" => 0, "gendar" => 0, "status" => "Active", "created_at" => "2024-10-24 10:05:08", "updated_at" => null, "deleted_at" => null],
            ["id" => 3, "creator_id" => 1, "editor_id" => null, "node_code" => "N003", "node_level_x" => 2, "node_level_y" => 1, "first_name" => "افشین", "last_name" => "نظری", "birth_date" => "2015-10-10 00:00:00", "death_date" => null, "is_owner" => 0, "gendar" => 1, "status" => "Active", "created_at" => "2024-10-24 10:05:08", "updated_at" => null, "deleted_at" => null],
            ["id" => 4, "creator_id" => 1, "editor_id" => null, "node_code" => "N004", "node_level_x" => 1, "node_level_y" => 1, "first_name" => "سوسن", "last_name" => "نظری", "birth_date" => "2018-11-10 00:00:00", "death_date" => null, "is_owner" => 0, "gendar" => 0, "status" => "Active", "created_at" => "2024-10-24 10:05:08", "updated_at" => null, "deleted_at" => null],
            ["id" => 5, "creator_id" => 1, "editor_id" => null, "node_code" => "N005", "node_level_x" => 1, "node_level_y" => 1, "first_name" => "محمد تقی", "last_name" => "نظری", "birth_date" => "1902-10-10 00:00:00", "death_date" => null, "is_owner" => 0, "gendar" => 1, "status" => "Active", "created_at" => "2024-10-24 10:05:08", "updated_at" => null, "deleted_at" => null],
            ["id" => 6, "creator_id" => 1, "editor_id" => null, "node_code" => "N006", "node_level_x" => 1, "node_level_y" => 1, "first_name" => "فریده", "last_name" => "رجبی", "birth_date" => "1950-11-10 00:00:00", "death_date" => null, "is_owner" => 0, "gendar" => 0, "status" => "Active", "created_at" => "2024-10-24 10:05:08", "updated_at" => null, "deleted_at" => null],
            ["id" => 7, "creator_id" => 1, "editor_id" => null, "node_code" => "Nas_48818812833", "node_level_x" => 0, "node_level_y" => 0, "first_name" => "آقا بابا", "last_name" => "پیرودین", "birth_date" => null, "death_date" => null, "is_owner" => 0, "gendar" => 1, "status" => "None", "created_at" => "2024-10-24 10:50:32", "updated_at" => "2024-10-24 10:50:32", "deleted_at" => null],
            ["id" => 8, "creator_id" => 1, "editor_id" => null, "node_code" => "Nas_60755634071", "node_level_x" => 0, "node_level_y" => 0, "first_name" => "سکینه", "last_name" => "پیرودین", "birth_date" => null, "death_date" => null, "is_owner" => 0, "gendar" => 1, "status" => "None", "created_at" => "2024-10-24 10:50:43", "updated_at" => "2024-10-24 10:50:43", "deleted_at" => null],
            ["id" => 9, "creator_id" => 1, "editor_id" => null, "node_code" => "Nas_67327940320", "node_level_x" => 0, "node_level_y" => 0, "first_name" => "اکبر", "last_name" => "پیرودین", "birth_date" => null, "death_date" => null, "is_owner" => 0, "gendar" => 1, "status" => "None", "created_at" => "2024-10-24 10:50:52", "updated_at" => "2024-10-24 10:50:52", "deleted_at" => null],
            ["id" => 10, "creator_id" => 1, "editor_id" => null, "node_code" => "Nas_43398199908", "node_level_x" => 0, "node_level_y" => 0, "first_name" => "اقدس", "last_name" => "محمدزاده", "birth_date" => null, "death_date" => null, "is_owner" => 0, "gendar" => 1, "status" => "None", "created_at" => "2024-10-24 10:51:09", "updated_at" => "2024-10-24 10:51:09", "deleted_at" => null],
            ["id" => 11, "creator_id" => 1, "editor_id" => null, "node_code" => "Nas_14290725631", "node_level_x" => 0, "node_level_y" => 0, "first_name" => "محمد میرزا", "last_name" => "محمدزاده", "birth_date" => null, "death_date" => null, "is_owner" => 0, "gendar" => 1, "status" => "None", "created_at" => "2024-10-24 10:51:27", "updated_at" => "2024-10-24 10:51:27", "deleted_at" => null],
            ["id" => 12, "creator_id" => 1, "editor_id" => null, "node_code" => "Nas_31518171273", "node_level_x" => 0, "node_level_y" => 0, "first_name" => "رقیه", "last_name" => "محمدزاده", "birth_date" => null, "death_date" => null, "is_owner" => 0, "gendar" => 1, "status" => "None", "created_at" => "2024-10-24 10:51:37", "updated_at" => "2024-10-24 10:51:37", "deleted_at" => null],
            ["id" => 13, "creator_id" => 1, "editor_id" => null, "node_code" => "Nas_17807671745", "node_level_x" => 0, "node_level_y" => 0, "first_name" => "محمد", "last_name" => "پیرودین", "birth_date" => null, "death_date" => null, "is_owner" => 0, "gendar" => 1, "status" => "None", "created_at" => "2024-10-24 10:52:35", "updated_at" => "2024-10-24 10:52:35", "deleted_at" => null],
            ["id" => 14, "creator_id" => 1, "editor_id" => null, "node_code" => "Nas_7662383121", "node_level_x" => 0, "node_level_y" => 0, "first_name" => "ملیکا", "last_name" => "پیرودین", "birth_date" => null, "death_date" => null, "is_owner" => 0, "gendar" => 1, "status" => "None", "created_at" => "2024-10-24 10:52:42", "updated_at" => "2024-10-24 10:52:42", "deleted_at" => null],
            ["id" => 15, "creator_id" => 1, "editor_id" => null, "node_code" => "Nas_72988276212", "node_level_x" => 0, "node_level_y" => 0, "first_name" => "مهدی", "last_name" => "پیرودین", "birth_date" => null, "death_date" => null, "is_owner" => 0, "gendar" => 1, "status" => "None", "created_at" => "2024-10-24 10:52:47", "updated_at" => "2024-10-24 10:52:47", "deleted_at" => null],
            ["id" => 16, "creator_id" => 1, "editor_id" => null, "node_code" => "Nas_986679763", "node_level_x" => 0, "node_level_y" => 0, "first_name" => "سارا", "last_name" => "شاطری", "birth_date" => null, "death_date" => null, "is_owner" => 0, "gendar" => 1, "status" => "None", "created_at" => "2024-10-24 10:53:06", "updated_at" => "2024-10-24 10:53:06", "deleted_at" => null],
            ["id" => 17, "creator_id" => 1, "editor_id" => null, "node_code" => "Nas_88564294400", "node_level_x" => 0, "node_level_y" => 0, "first_name" => "آصف", "last_name" => "یونسی", "birth_date" => null, "death_date" => null, "is_owner" => 0, "gendar" => 1, "status" => "None", "created_at" => "2024-10-24 10:53:20", "updated_at" => "2024-10-24 10:53:20", "deleted_at" => null],
            ["id" => 18, "creator_id" => 1, "editor_id" => null, "node_code" => "Nas_22500985194", "node_level_x" => 0, "node_level_y" => 0, "first_name" => "الیکا", "last_name" => "یونسی", "birth_date" => null, "death_date" => null, "is_owner" => 0, "gendar" => 1, "status" => "None", "created_at" => "2024-10-24 10:53:25", "updated_at" => "2024-10-24 10:53:25", "deleted_at" => null],
            ["id" => 19, "creator_id" => 1, "editor_id" => null, "node_code" => "Nas_36316149738", "node_level_x" => 0, "node_level_y" => 0, "first_name" => "کامران", "last_name" => "کامرانی", "birth_date" => null, "death_date" => null, "is_owner" => 0, "gendar" => 1, "status" => "None", "created_at" => "2024-10-24 12:33:17", "updated_at" => "2024-10-24 12:33:17", "deleted_at" => null],
            ["id" => 20, "creator_id" => 1, "editor_id" => null, "node_code" => "Nas_75854337803", "node_level_x" => 0, "node_level_y" => 0, "first_name" => "امیر", "last_name" => "کامرانی", "birth_date" => null, "death_date" => null, "is_owner" => 0, "gendar" => 1, "status" => "None", "created_at" => "2024-10-24 12:33:30", "updated_at" => "2024-10-24 12:33:30", "deleted_at" => null],
            ["id" => 29, "creator_id" => 1, "editor_id" => null, "node_code" => "333", "node_level_x" => 1, "node_level_y" => 1, "first_name" => "مریم", "last_name" => "مریمی", "birth_date" => null, "death_date" => null, "is_owner" => 0, "gendar" => 1, "status" => "None", "created_at" => null, "updated_at" => null, "deleted_at" => null],
            ["id" => 32, "creator_id" => 1, "editor_id" => null, "node_code" => "777", "node_level_x" => 1, "node_level_y" => 1, "first_name" => "حسن", "last_name" => "پیرودین", "birth_date" => null, "death_date" => null, "is_owner" => 0, "gendar" => 1, "status" => "None", "created_at" => null, "updated_at" => null, "deleted_at" => null],
            ["id" => 35, "creator_id" => 1, "editor_id" => null, "node_code" => "001", "node_level_x" => 1, "node_level_y" => 1, "first_name" => "مریم 2", "last_name" => "مریمی 2", "birth_date" => null, "death_date" => null, "is_owner" => 0, "gendar" => 1, "status" => "None", "created_at" => null, "updated_at" => null, "deleted_at" => null],
            ["id" => 36, "creator_id" => 1, "editor_id" => null, "node_code" => "7777", "node_level_x" => 1, "node_level_y" => 1, "first_name" => "2 حسن", "last_name" => "پیرودین", "birth_date" => null, "death_date" => null, "is_owner" => 0, "gendar" => 1, "status" => "None", "created_at" => null, "updated_at" => null, "deleted_at" => null],
            ["id" => 40, "creator_id" => 1, "editor_id" => null, "node_code" => "2312", "node_level_x" => 1, "node_level_y" => 1, "first_name" => "سارا ۲", "last_name" => "شاطری ۲", "birth_date" => null, "death_date" => null, "is_owner" => 0, "gendar" => 1, "status" => "None", "created_at" => null, "updated_at" => null, "deleted_at" => null],
            ["id" => 41, "creator_id" => 1, "editor_id" => null, "node_code" => "2222", "node_level_x" => 1, "node_level_y" => 1, "first_name" => "ملیکا ۲", "last_name" => "پیرودین", "birth_date" => null, "death_date" => null, "is_owner" => 0, "gendar" => 1, "status" => "None", "created_at" => null, "updated_at" => null, "deleted_at" => null],
            ["id" => 42, "creator_id" => 1, "editor_id" => null, "node_code" => "1", "node_level_x" => 1, "node_level_y" => 1, "first_name" => "بهرام", "last_name" => "یونسی", "birth_date" => null, "death_date" => null, "is_owner" => 0, "gendar" => 1, "status" => "None", "created_at" => null, "updated_at" => null, "deleted_at" => null],
            ["id" => 43, "creator_id" => 1, "editor_id" => null, "node_code" => "۲", "node_level_x" => 1, "node_level_y" => 1, "first_name" => "فاطمه", "last_name" => "توکلی", "birth_date" => null, "death_date" => null, "is_owner" => 0, "gendar" => 1, "status" => "None", "created_at" => null, "updated_at" => null, "deleted_at" => null],
            ["id" => 44, "creator_id" => 1, "editor_id" => null, "node_code" => "3", "node_level_x" => 1, "node_level_y" => 1, "first_name" => "آیه", "last_name" => "یونسی", "birth_date" => null, "death_date" => null, "is_owner" => 0, "gendar" => 1, "status" => "None", "created_at" => null, "updated_at" => null, "deleted_at" => null]
        ];
        

        foreach ($data as $person) {
            Person::create($person);
        }
    }
}
