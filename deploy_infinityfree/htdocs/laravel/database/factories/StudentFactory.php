<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'nisn' => fake()->unique()->numerify('00##########'),
            'nis' => fake()->unique()->numerify('########'),
            'birth_place' => fake()->city(),
            'birth_date' => fake()->dateTimeBetween('-18 years', '-14 years'),
            'parent_name' => fake()->name(),
            'parent_phone' => fake()->numerify('08##########'),
            'admission_year' => fake()->numberBetween(2022, 2025),
            'status' => 'active',
        ];
    }
}
