<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Member;

class SubscriptionFactory extends Factory
{
    public function definition()
    {
        $start = $this->faker->dateTimeBetween('-6 months', 'now');
        $end   = (clone $start)->modify('+1 month');

        return [
            'member_id'  => Member::inRandomOrder()->first()?->id,
            'plan_name'  => $this->faker->randomElement(['Monthly Plan', 'Quarterly Plan', 'Annual Plan']),
            'price'      => $this->faker->randomElement([30, 75, 250]),
            'start_date' => $start,
            'end_date'   => $end,
            'status'     => $this->faker->randomElement(['active', 'expired', 'pending']),
        ];
    }
}
