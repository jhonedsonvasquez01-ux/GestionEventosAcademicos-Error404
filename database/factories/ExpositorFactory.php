<?php

namespace Database\Factories;

use App\Models\Expositor;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Expositor> */
class ExpositorFactory extends Factory
{
    protected $model = Expositor::class;

    public function definition(): array
    {
        return [
            'nombre' => fake()->firstName(),
            'apellido' => fake()->lastName(),
            'especialidad' => fake()->randomElement(['Desarrollo web', 'Bases de datos', 'Ciberseguridad', 'Inteligencia artificial', 'Gestión de proyectos']),
            'institucion' => fake()->company(),
            'email' => fake()->unique()->safeEmail(),
        ];
    }
}