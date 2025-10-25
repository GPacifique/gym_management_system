<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Trainer;

class MemberFactory extends Factory
{
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name'  => $this->faker->lastName(),
            'email'      => $this->faker->unique()->safeEmail(),
            'phone'      => $this->faker->phoneNumber(),
            'dob'        => $this->faker->date('Y-m-d', '-18 years'),
            'gender'     => $this->faker->randomElement(['male', 'female', 'other']),
            'address'    => $this->faker->address(),
            'join_date'  => $this->faker->dateTimeBetween('-1 year', 'now'),
            'trainer_id' => Trainer::inRandomOrder()->first()?->id,
        ];
    }
}
