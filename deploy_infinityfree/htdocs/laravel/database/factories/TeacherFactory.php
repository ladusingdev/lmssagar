<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeacherFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'nip' => fake()->unique()->numerify('19##########3001'),
            'nuptk' => fake()->unique()->numerify('####################'),
            'birth_place' => fake()->city(),
            'birth_date' => fake()->dateTimeBetween('-55 years', '-25 years'),
            'employment_status' => fake()->randomElement(['PNS', 'PPPK', 'Honorer', 'GTT']),
        ];
    }
}
