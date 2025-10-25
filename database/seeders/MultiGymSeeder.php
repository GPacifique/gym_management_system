<?php

namespace Database\Seeders;

use App\Models\Gym;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MultiGymSeeder extends Seeder
{
    public function run(): void
    {
        // Create 3 sample gyms
        $gym1 = Gym::create([
            'name' => 'Downtown Fitness Center',
            'slug' => 'downtown-fitness',
            'address' => '123 Main St, Downtown',
            'phone' => '(555) 111-2222',
            'timezone' => 'America/New_York',
        ]);

        $gym2 = Gym::create([
            'name' => 'Westside Athletic Club',
            'slug' => 'westside-athletic',
            'address' => '456 West Ave, Westside',
            'phone' => '(555) 333-4444',
            'timezone' => 'America/New_York',
        ]);

        $gym3 = Gym::create([
            'name' => 'Eastside Performance Gym',
            'slug' => 'eastside-performance',
            'address' => '789 East Blvd, Eastside',
            'phone' => '(555) 555-6666',
            'timezone' => 'America/New_York',
        ]);

        // Create super admin (access to all gyms)
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@gymmate.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'gym_id' => $gym1->id,
            'default_gym_id' => $gym1->id,
        ]);
        
        // Attach to all gyms
        $superAdmin->gyms()->attach([
            $gym1->id => ['role' => 'admin'],
            $gym2->id => ['role' => 'admin'],
            $gym3->id => ['role' => 'admin'],
        ]);

        // Gym 1 staff
        $gym1Manager = User::create([
            'name' => 'Downtown Manager',
            'email' => 'manager.downtown@gymmate.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'gym_id' => $gym1->id,
            'default_gym_id' => $gym1->id,
        ]);
        $gym1Manager->gyms()->attach($gym1->id, ['role' => 'manager']);

        $gym1Receptionist = User::create([
            'name' => 'Downtown Receptionist',
            'email' => 'receptionist.downtown@gymmate.com',
            'password' => Hash::make('password'),
            'role' => 'receptionist',
            'gym_id' => $gym1->id,
            'default_gym_id' => $gym1->id,
        ]);
        $gym1Receptionist->gyms()->attach($gym1->id, ['role' => 'receptionist']);

        // Gym 2 staff
        $gym2Manager = User::create([
            'name' => 'Westside Manager',
            'email' => 'manager.westside@gymmate.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'gym_id' => $gym2->id,
            'default_gym_id' => $gym2->id,
        ]);
        $gym2Manager->gyms()->attach($gym2->id, ['role' => 'manager']);

        $gym2Trainer = User::create([
            'name' => 'Westside Trainer',
            'email' => 'trainer.westside@gymmate.com',
            'password' => Hash::make('password'),
            'role' => 'trainer',
            'gym_id' => $gym2->id,
            'default_gym_id' => $gym2->id,
        ]);
        $gym2Trainer->gyms()->attach($gym2->id, ['role' => 'trainer']);

        // Gym 3 staff
        $gym3Manager = User::create([
            'name' => 'Eastside Manager',
            'email' => 'manager.eastside@gymmate.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'gym_id' => $gym3->id,
            'default_gym_id' => $gym3->id,
        ]);
        $gym3Manager->gyms()->attach($gym3->id, ['role' => 'manager']);

        // Multi-gym trainer (works at gym1 and gym2)
        $multiTrainer = User::create([
            'name' => 'Multi-Location Trainer',
            'email' => 'trainer.multi@gymmate.com',
            'password' => Hash::make('password'),
            'role' => 'trainer',
            'gym_id' => $gym1->id,
            'default_gym_id' => $gym1->id,
        ]);
        $multiTrainer->gyms()->attach([
            $gym1->id => ['role' => 'trainer'],
            $gym2->id => ['role' => 'trainer'],
        ]);

        $this->command->info('✓ Created 3 gyms with assigned staff');
        $this->command->info('✓ Super Admin: admin@gymmate.com / password');
        $this->command->info('✓ Gym 1 Manager: manager.downtown@gymmate.com / password');
        $this->command->info('✓ Gym 2 Manager: manager.westside@gymmate.com / password');
        $this->command->info('✓ Multi-gym Trainer: trainer.multi@gymmate.com / password');
    }
}
