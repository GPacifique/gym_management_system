<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Member;
use App\Models\GymClass;

class AttendanceFactory extends Factory
{
    public function definition()
    {
        return [
            'member_id' => Member::inRandomOrder()->first()?->id,
            'class_id'  => GymClass::inRandomOrder()->first()?->id,
            'attended'  => $this->faker->boolean(70), // 70% attended
        ];
    }
}
