<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Member;
use App\Models\GymClass;

class AttendanceFactory extends Factory
{
    public function definition()
    {
        $checkIn = $this->faker->dateTimeBetween('-2 months', 'now');
        $checkedOut = $this->faker->boolean(70);
        return [
            'member_id' => Member::inRandomOrder()->first()?->id,
            'class_id'  => GymClass::inRandomOrder()->first()?->id,
            'check_in_time' => $checkIn,
            'check_out_time' => $checkedOut ? (clone $checkIn)->modify('+'.rand(30,180).' minutes') : null,
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
