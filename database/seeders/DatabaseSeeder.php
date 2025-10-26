<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Super Admin if not exists
        User::firstOrCreate(
            ['email' => 'superadmin@gymsystem.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'email_verified_at' => now(),
            ]
        );

        $this->call(RoleSeeder::class);
        $this->call(GymSeeder::class);
        // Ensure each gym has at least one branch
        $this->call(BranchBackfillSeeder::class);
    }
}
