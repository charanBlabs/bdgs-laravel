<?php

namespace Database\Seeders;

use App\Models\BdgsRole;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'admin', 'display_name' => 'Administrator', 'description' => 'Full site access'],
            ['name' => 'user', 'display_name' => 'User', 'description' => 'Customer dashboard access'],
        ];

        foreach ($roles as $role) {
            BdgsRole::query()->updateOrCreate(['name' => $role['name']], $role);
        }
    }
}
