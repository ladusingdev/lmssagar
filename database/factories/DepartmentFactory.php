<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Rekayasa Perangkat Lunak', 'Teknik Komputer dan Jaringan', 'Multimedia',
            'Akuntansi', 'Otomatisasi dan Tata Kelola Perkantoran', 'Teknik Kendaraan Ringan',
        ]);

        return [
            'name' => $name,
            'code' => \Illuminate\Support\Str::of($name)->explode(' ')->map(fn ($w) => strtoupper($w[0]))->implode(''),
            'description' => fake()->sentence(12),
        ];
    }
}
