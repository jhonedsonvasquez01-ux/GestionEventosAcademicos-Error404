<?php

namespace Database\Seeders;

use App\Models\Actividad;
use App\Models\Asistencia;
use App\Models\Certificado;
use App\Models\Evento;
use App\Models\Expositor;
use App\Models\Inscripcion;
use App\Models\Participante;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {

            // Crear usuario administrador de prueba
            User::firstOrCreate(
                ['email' => 'admin@eventos.test'],
                [
                    'name' => 'Administrador de prueba',
                    'password' => Hash::make('Password123!'),
                ]
            );

            // Crear eventos, expositores y participantes
            $eventos = Evento::factory(5)->create();
            $expositores = Expositor::factory(10)->create();
            $participantes = Participante::factory(30)->create();

            foreach ($eventos as $evento) {

                // Crear actividades para cada evento
                $actividades = Actividad::factory(
                    fake()->numberBetween(3, 5)
                )->create([
                    'evento_id' => $evento->id,
                    'fecha' => $evento->fecha_inicio,
                ]);

                // Asignar un expositor a cada actividad
                foreach ($actividades as $actividad) {
                    $actividad->expositores()->attach(
                        $expositores->random()->id
                    );
                }

                // Inscribir participantes
                foreach ($participantes->random(10) as $participante) {

                    $inscripcion = Inscripcion::firstOrCreate(
                        [
                            'participante_id' => $participante->id,
                            'evento_id' => $evento->id,
                        ],
                        [
                            'fecha_inscripcion' => now()->toDateString(),
                            'estado' => 'confirmado',
                        ]
                    );

                    // Elegir una actividad
                    $actividadElegida = $actividades->random();

                    $inscripcion->actividades()->syncWithoutDetaching([
                        $actividadElegida->id,
                    ]);

                    // Registrar asistencia
                    $asistio = fake()->boolean(75);

                    Asistencia::firstOrCreate(
                        [
                            'inscripcion_id' => $inscripcion->id,
                            'actividad_id' => $actividadElegida->id,
                        ],
                        [
                            'asistio' => $asistio,
                            'fecha_hora_registro' => now(),
                        ]
                    );

                    // Calcular porcentaje de asistencia
                    $totalActividades = $inscripcion->actividades()->count();

                    $asistencias = Asistencia::where(
                        'inscripcion_id',
                        $inscripcion->id
                    )
                    ->where('asistio', true)
                    ->count();

                    $porcentaje = $totalActividades > 0
                        ? ($asistencias / $totalActividades) * 100
                        : 0;

                    // Emitir certificado solo con 80% o más
                    if ($porcentaje >= 80) {

                        Certificado::firstOrCreate(
                            ['inscripcion_id' => $inscripcion->id],
                            [
                                'codigo' => 'CERT-' . Str::upper(Str::random(12)),
                                'fecha_emision' => now()->toDateString(),
                            ]
                        );
                    }
                }
            }
        });
    }
}
