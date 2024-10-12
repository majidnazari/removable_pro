<?php

namespace Database\Seeders;

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
            SubscriptionSeeder::class,
            PersonSeeder::class,
            PersonDetailSeeder::class,
            PersonSpouseSeeder::class,
            FavoriteSeeder::class,
            GroupViewSeeder::class,
            MemorySeeder::class,
            PersonChildSeeder::class,
            FamilyEventSeeder::class,
            ScoreSeeder::class,
            PersonScoreSeeder::class,
            UserSubscriptionSeeder::class,
            ClanSeeder::class,
            ClanMemberSeeder::class,
            VolumeExtraSeeder::class,
            UserVolumeExtraSeeder::class,

            CountrySeeder::class,
            ProvinceSeeder::class,
            CitySeeder::class,
            AreaSeeder::class,
            AddressSeeder::class,
            FamilyBoardSeeder::class,

            QuestionSeeder::class,
            UserAnswerSeeder::class,
            UserMobileSeeder::class,
            IpTrackingSeeder::class

        ]);
    }
}