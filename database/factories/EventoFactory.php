<?php

namespace Database\Factories;

use App\Models\Evento;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Evento> */
class EventoFactory extends Factory
{
    protected $model = Evento::class;

    public function definition(): array
    {
        $inicio = fake()->dateTimeBetween('+7 days', '+90 days');

        return [
            'nombre' => fake()->unique()->sentence(3),
            'fecha_inicio' => $inicio->format('Y-m-d'),
            'fecha_fin' => (clone $inicio)->modify('+' . rand(1, 3) . ' days')->format('Y-m-d'),
            'lugar' => fake()->city() . ' - ' . fake()->streetName(),
            'costo_inscripcion' => fake()->randomElement([0, 25, 50, 75, 100]),
        ];
    }
}