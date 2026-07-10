<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            AdminUserSeeder::class,
            DataTypeSeeder::class,
            SolutionCategorySeeder::class,
            SolutionDataSeeder::class,
            EmailTemplateSeeder::class,
            WebsiteSettingsSeeder::class,
            ZoomClinicSeeder::class,
        ]);
    }
}
