<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or update seed users idempotently
        User::updateOrCreate(
            ['email' => 'admin@gymmate.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'manager@gymmate.com'],
            [
                'name' => 'Manager User',
                'password' => Hash::make('password'),
                'role' => 'manager',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'receptionist@gymmate.com'],
            [
                'name' => 'Receptionist User',
                'password' => Hash::make('password'),
                'role' => 'receptionist',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'trainer@gymmate.com'],
            [
                'name' => 'Trainer User',
                'password' => Hash::make('password'),
                'role' => 'trainer',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'member@gymmate.com'],
            [
                'name' => 'Member User',
                'password' => Hash::make('password'),
                'role' => 'member',
                'email_verified_at' => now(),
            ]
        );
    }
}
