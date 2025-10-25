<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Member;
use App\Models\Subscription;

class PaymentFactory extends Factory
{
    public function definition()
    {
        return [
            'member_id'        => Member::inRandomOrder()->first()?->id,
            'subscription_id'  => Subscription::inRandomOrder()->first()?->id,
            'amount'           => $this->faker->randomFloat(2, 20, 300),
            'method'           => $this->faker->randomElement(['cash', 'card', 'online']),
            'payment_date'     => $this->faker->dateTimeBetween('-3 months', 'now'),
            'transaction_id'   => strtoupper($this->faker->bothify('TXN###??')),
        ];
    }
}
