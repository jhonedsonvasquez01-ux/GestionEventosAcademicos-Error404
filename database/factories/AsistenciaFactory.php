<?php

namespace Database\Factories;

use App\Models\Actividad;
use App\Models\Asistencia;
use App\Models\Inscripcion;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Asistencia> */
class AsistenciaFactory extends Factory
{
    protected $model = Asistencia::class;

    public function definition(): array
    {
        return [
            'inscripcion_id' => Inscripcion::factory(),
            'actividad_id' => Actividad::factory(),
            'asistio' => fake()->boolean(75),
            'fecha_hora_registro' => fake()->optional()->dateTimeThisYear(),
        ];
    }
}