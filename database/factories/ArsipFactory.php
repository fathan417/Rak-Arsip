<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ArsipFactory extends Factory
{
    public function definition(): array
    {
        $judul = $this->faker->sentence(4);
        return [
            'judul' => $judul,
            'pengarang' => $this->faker->name(),
            'abstrak' => $this->faker->paragraph(3),
            'lokasi_rak' => 'Rak ' . $this->faker->numberBetween(1, 5),
            'lokasi_baris' => 'Baris ' . $this->faker->numberBetween(1, 10),
            'thumbnail_path' => null,
            'slug' => Str::slug($judul . '-' . Str::random(5)),
            'published_at' => $this->faker->dateTimeBetween('-5 years', 'now'),
        ];
    }
}
