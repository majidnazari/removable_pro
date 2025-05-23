<?php

namespace Database\Seeders;

use App\Models\TalentHeader;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->Call([
            UserSeeder::class,
            CategoryContentSeeder::class,
            EventSeeder::class,
            PersonSeeder::class,
            PersonDetailSeeder::class,
            PersonMarriageSeeder::class,
            FavoriteSeeder::class,
            GroupSeeder::class,
            GroupDetailSeeder::class,
            GroupCategorySeeder::class,
            GroupCategoryDetailSeeder::class,
            MemorySeeder::class,
            PersonChildSeeder::class,
                // FamilyEventSeeder::class,
            //ScoreSeeder::class,
            //PersonScoreSeeder::class,

            CountrySeeder::class,
            ProvinceSeeder::class,
            CitySeeder::class,
            AddressSeeder::class,
                //FamilyBoardSeeder::class,
            UserMergeRequestSeeder::class,
            MajorFieldSeeder::class,
            MiddleFieldSeeder::class,
            MinorFieldSeeder::class,
            TalentHeaderSeeder::class,
            TalentDetailSeeder::class,
            TalentDetailScoreSeeder::class,

            // NaslanRelationshipSeeder::class,

        ]);
    }
}