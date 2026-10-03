<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            LocationSeeder::class,
            ServiceSeeder::class,
            SettingSeeder::class,
            FaqSeeder::class,
            UserAndCaregiverSeeder::class,
            SampleEngagementSeeder::class,
        ]);
    }
}
