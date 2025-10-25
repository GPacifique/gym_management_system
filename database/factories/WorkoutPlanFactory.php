<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Member;
use App\Models\Trainer;

class WorkoutPlanFactory extends Factory
{
    public function definition()
    {
        return [
            'member_id'  => Member::inRandomOrder()->first()?->id,
            'trainer_id' => Trainer::inRandomOrder()->first()?->id,
            'plan_name'  => $this->faker->randomElement(['Beginner Strength', 'Cardio Blast', 'Yoga Routine']),
            'description'=> $this->faker->paragraph(),
            'start_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'end_date'   => $this->faker->dateTimeBetween('now', '+1 month'),
        ];
    }
}
