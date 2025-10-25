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
        // Create Admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@gymmate.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create Manager user
        User::create([
            'name' => 'Manager User',
            'email' => 'manager@gymmate.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'email_verified_at' => now(),
        ]);

        // Create Receptionist user
        User::create([
            'name' => 'Receptionist User',
            'email' => 'receptionist@gymmate.com',
            'password' => Hash::make('password'),
            'role' => 'receptionist',
            'email_verified_at' => now(),
        ]);

        // Create Trainer user
        User::create([
            'name' => 'Trainer User',
            'email' => 'trainer@gymmate.com',
            'password' => Hash::make('password'),
            'role' => 'trainer',
            'email_verified_at' => now(),
        ]);

        // Create Member user
        User::create([
            'name' => 'Member User',
            'email' => 'member@gymmate.com',
            'password' => Hash::make('password'),
            'role' => 'member',
            'email_verified_at' => now(),
        ]);
    }
}
