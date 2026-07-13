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

        $adminEmail = (string) env('ADMIN_EMAIL', 'admin@bdgrowthsuite.com');
        $adminPassword = (string) env('ADMIN_PASSWORD', '');

        // Testing always has a known password (phpunit.xml); local/prod require .env.
        if ($adminPassword === '' && app()->environment('testing')) {
            $adminPassword = 'ChangeMe123!';
        }

        if ($adminPassword === '') {
            $this->command?->error('ADMIN_PASSWORD not set in .env — skipping admin user creation.');

            return;
        }

        $admin = User::query()->firstOrNew(['email' => $adminEmail]);
        $admin->fill([
            'first_name' => 'BDGS',
            'last_name' => 'Admin',
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $admin->password = $adminPassword;
        $admin->save();

        if ($adminRole) {
            $admin->roles()->syncWithoutDetaching([$adminRole->id]);
        }

        BdgsUserData::query()->updateOrCreate(
            ['user_id' => $admin->id],
            ['company' => 'BD Growth Suite', 'position' => 'Administrator']
        );

        if (app()->environment('local', 'testing') || app()->runningUnitTests()) {
            $demo = User::query()->firstOrNew(['email' => 'demo@bdgrowthsuite.com']);
            $demo->fill([
                'first_name' => 'Demo',
                'last_name' => 'Customer',
                'email_verified_at' => now(),
                'is_active' => true,
            ]);
            $demo->password = 'DemoUser123!';
            $demo->save();

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
