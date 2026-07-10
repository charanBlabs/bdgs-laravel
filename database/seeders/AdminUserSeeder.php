<?php

namespace Database\Seeders;

use App\Models\BdgsRole;
use App\Models\BdgsUserData;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = BdgsRole::query()->where('name', 'admin')->first();
        $userRole = BdgsRole::query()->where('name', 'user')->first();

        $adminPassword = env('ADMIN_PASSWORD');
        if (! $adminPassword) {
            $this->command?->error('ADMIN_PASSWORD not set in .env — skipping admin user creation.');

            return;
        }

        $admin = User::query()->updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@bdgrowthsuite.com')],
            [
                'first_name' => 'BDGS',
                'last_name' => 'Admin',
                'password' => $adminPassword,
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );

        if ($adminRole) {
            $admin->roles()->syncWithoutDetaching([$adminRole->id]);
        }

        BdgsUserData::query()->updateOrCreate(
            ['user_id' => $admin->id],
            ['company' => 'BD Growth Suite', 'position' => 'Administrator']
        );

        if (app()->environment('local', 'testing')) {
            $demo = User::query()->updateOrCreate(
                ['email' => 'demo@bdgrowthsuite.com'],
                [
                    'first_name' => 'Demo',
                    'last_name' => 'Customer',
                    'password' => 'DemoUser-'.bin2hex(random_bytes(4)),
                    'email_verified_at' => now(),
                    'is_active' => true,
                ]
            );

            if ($userRole) {
                $demo->roles()->syncWithoutDetaching([$userRole->id]);
            }

            BdgsUserData::query()->updateOrCreate(
                ['user_id' => $demo->id],
                ['company' => 'Demo Directory', 'phone' => '+1 555 0100']
            );
        }
    }
}
