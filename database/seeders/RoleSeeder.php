<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['slug' => 'admin', 'name' => 'Administrator', 'description' => 'Platform administrator with full control.'],
            ['slug' => 'caregiver', 'name' => 'Caregiver', 'description' => 'Verified professional care provider.'],
            ['slug' => 'client', 'name' => 'Client / Family', 'description' => 'Family member seeking verified care.'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['slug' => $role['slug']], $role);
        }
    }
}
