<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AcademicYearFactory extends Factory
{
    public function definition(): array
    {
        $startYear = fake()->numberBetween(2023, 2025);

        return [
            'name' => "{$startYear}/".($startYear + 1),
            'semester' => fake()->randomElement(['Ganjil', 'Genap']),
            'start_date' => "{$startYear}-07-01",
            'end_date' => ($startYear + 1).'-06-30',
            'is_active' => false,
        ];
    }
}
