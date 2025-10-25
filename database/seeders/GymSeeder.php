<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{
    Trainer, Member, Subscription, Payment, WorkoutPlan, GymClass, Attendance
};

class GymSeeder extends Seeder
{
    public function run()
    {
        // Create Trainers
        Trainer::factory(5)->create();

        // Create Members
        Member::factory(50)->create();

        // Create Subscriptions
        Subscription::factory(80)->create();

        // Create Payments
        Payment::factory(100)->create();

        // Create Workout Plans
        WorkoutPlan::factory(40)->create();

        // Create Classes
        GymClass::factory(10)->create();

        // Create Attendances
        Attendance::factory(100)->create();
    }
}
