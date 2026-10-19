<?php

namespace Database\Seeders;

use App\Models\Actividad;
use App\Models\Asistencia;
use App\Models\Certificado;
use App\Models\Evento;
use App\Models\Inscripcion;
use App\Models\Participante;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatosMasivosSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            // Crear participantes adicionales
            $participantes = Participante::factory(350)->create();

            $eventos = Evento::all();

            foreach ($participantes as $participante) {

                // Actividades con cupo disponible
                $actividades = Actividad::whereIn(
                    'evento_id',
                    $eventos->pluck('id')
                )->get()->filter(function ($actividad) {
                    return $actividad->inscripciones()->count()
                        < $actividad->cupo;
                });

                if ($actividades->isEmpty()) {
                    continue;
                }

                // Una actividad por inscripción: sin cruces de horario
                $actividad = $actividades->random();

                $inscripcion = Inscripcion::create([
                    'participante_id' => $participante->id,
                    'evento_id' => $actividad->evento_id,
                    'fecha_inscripcion' => now()->toDateString(),
                    'estado' => 'confirmado',
                ]);

                $inscripcion->actividades()->attach($actividad->id);

                // Registrar asistencia
                $asistio = fake()->boolean(80);

                Asistencia::create([
                    'inscripcion_id' => $inscripcion->id,
                    'actividad_id' => $actividad->id,
                    'asistio' => $asistio,
                    'fecha_hora_registro' => now(),
                ]);

                // Con una actividad, asistir equivale al 100%
                if ($asistio) {
                    Certificado::create([
                        'inscripcion_id' => $inscripcion->id,
                        'codigo' => 'CERT-' . Str::upper(Str::random(12)),
                        'fecha_emision' => now()->toDateString(),
                    ]);
                }
            }
        });

        $this->command->info('Carga masiva finalizada.');
    }
}