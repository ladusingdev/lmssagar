<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SubjectFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Matematika', 'Bahasa Indonesia', 'Bahasa Inggris', 'Pendidikan Agama',
            'Pendidikan Pancasila', 'Pemrograman Web', 'Basis Data', 'Pemrograman Berorientasi Objek',
            'Jaringan Komputer', 'Desain Grafis', 'Produk Kreatif dan Kewirausahaan',
            'Sistem Komputer', 'IPAS', 'Sejarah Indonesia', 'Seni Budaya', 'PJOK',
        ]);

        return [
            'name' => $name,
            'code' => strtoupper(\Illuminate\Support\Str::slug($name, '')),
            'description' => fake()->sentence(10),
        ];
    }
}
