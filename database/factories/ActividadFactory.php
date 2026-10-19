<?php

namespace Database\Factories;

use App\Models\Actividad;
use App\Models\Evento;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Actividad> */
class ActividadFactory extends Factory
{
    protected $model = Actividad::class;

    public function definition(): array
    {
        $inicio = fake()->dateTimeBetween('09:00', '14:00');

        return [
            'evento_id' => Evento::factory(),
            'nombre' => fake()->randomElement(['Taller de Laravel', 'Conferencia de ciberseguridad', 'Seminario de inteligencia artificial', 'Taller de bases de datos', 'Charla de innovación']),
            'tipo' => fake()->randomElement(['Taller', 'Conferencia', 'Seminario', 'Charla']),
            'fecha' => fake()->dateTimeBetween('+7 days', '+90 days')->format('Y-m-d'),
            'hora_inicio' => $inicio->format('H:i:s'),
            'hora_fin' => (clone $inicio)->modify('+2 hours')->format('H:i:s'),
            'sala' => 'Sala ' . fake()->numberBetween(1, 8),
            'cupo' => fake()->numberBetween(20, 120),
        ];
    }
}