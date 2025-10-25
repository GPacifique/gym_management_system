<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TrainerFactory extends Factory
{
    public function definition()
    {
        return [
            'name'            => $this->faker->name(),
            'email'           => $this->faker->unique()->safeEmail(),
            'phone'           => $this->faker->phoneNumber(),
            'specialization'  => $this->faker->randomElement(['Cardio', 'Strength', 'Yoga', 'CrossFit', 'Zumba']),
            'salary'          => $this->faker->randomFloat(2, 800, 2000),
        ];
    }
}
