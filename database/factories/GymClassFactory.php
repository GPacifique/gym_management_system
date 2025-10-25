<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Trainer;

class GymClassFactory extends Factory
{
    public function definition()
    {
        return [
            'class_name' => $this->faker->randomElement(['Morning Yoga', 'CrossFit Session', 'Evening Zumba']),
            'trainer_id' => Trainer::inRandomOrder()->first()?->id,
            'location'   => $this->faker->randomElement(['Studio A', 'Studio B', 'Outdoor']),
            'scheduled_at' => $this->faker->dateTimeBetween('now', '+1 week'),
            'capacity'   => $this->faker->numberBetween(10, 30),
        ];
    }
}
