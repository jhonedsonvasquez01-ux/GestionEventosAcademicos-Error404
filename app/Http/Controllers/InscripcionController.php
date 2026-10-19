<?php

namespace App\Http\Controllers;

use App\Models\Inscripcion;
use App\Models\Participante;
use App\Models\Evento;
use App\Models\Actividad;
use Illuminate\Http\Request;

class InscripcionController extends Controller
{
    public function index()
    {
        $inscripciones = Inscripcion::with([
            'participante',
            'evento',
            'actividades'
        ])->latest()->paginate(10);

        return view('inscripciones.index', compact('inscripciones'));
    }

    public function create()
    {
        $participantes = Participante::orderBy('apellido')->get();
        $eventos = Evento::with('actividades')->orderBy('nombre')->get();

        return view('inscripciones.create', compact('participantes', 'eventos'));
    }

    // Verificar si dos actividades tienen horarios cruzados
    private function horariosCruzados(Actividad $a, Actividad $b): bool
    {
        return
            $a->fecha->toDateString() === $b->fecha->toDateString()
            && $a->hora_inicio < $b->hora_fin
            && $b->hora_inicio < $a->hora_fin;
    }

    // Validar actividades seleccionadas y actividades de otros eventos
    private function validarHorarios(
        $actividades,
        int $participanteId,
        ?int $inscripcionActualId = null
    ): ?string {

        // Comprobar cruces entre las actividades seleccionadas
        foreach ($actividades as $actividad1) {
            foreach ($actividades as $actividad2) {

                if ($actividad1->id >= $actividad2->id) {
                    continue;
                }

                if ($this->horariosCruzados($actividad1, $actividad2)) {
                    return 'Las actividades "' .
                        $actividad1->nombre . '" y "' .
                        $actividad2->nombre .
                        '" tienen horarios cruzados.';
                }
            }
        }

        // Buscar actividades de otras inscripciones del participante
        $otrasInscripciones = Inscripcion::with('actividades')
            ->where('participante_id', $participanteId)
            ->where('estado', '!=', 'anulado')
            ->when($inscripcionActualId, function ($consulta) use ($inscripcionActualId) {
                $consulta->where('id', '!=', $inscripcionActualId);
            })
            ->get();

        foreach ($actividades as $nuevaActividad) {
            foreach ($otrasInscripciones as $otraInscripcion) {
                foreach ($otraInscripcion->actividades as $actividadExistente) {

                    if ($this->horariosCruzados($nuevaActividad, $actividadExistente)) {
                        return 'La actividad "' .
                            $nuevaActividad->nombre .
                            '" coincide con "' .
                            $actividadExistente->nombre .
                            '", que el participante ya tiene registrada.';
                    }
                }
            }
        }

        return null;
    }

    public function store(Request $r)
    {
        $d = $r->validate([
            'participante_id' => 'required|exists:participantes,id',
            'evento_id' => 'required|exists:eventos,id',
            'fecha_inscripcion' => 'required|date',
            'estado' => 'required|in:inscrito,confirmado,anulado',
            'actividades' => 'array',
            'actividades.*' => 'integer|distinct|exists:actividades,id',
        ]);

        // Evitar inscripciones duplicadas
        if (
            Inscripcion::where('participante_id', $d['participante_id'])
                ->where('evento_id', $d['evento_id'])
                ->exists()
        ) {
            return back()->withInput()->withErrors([
                'participante_id' => 'Este participante ya está inscrito en ese evento.'
            ]);
        }

        $ids = $d['actividades'] ?? [];

        // Comprobar que las actividades pertenezcan al evento
        $validos = Actividad::where('evento_id', $d['evento_id'])
            ->whereIn('id', $ids)
            ->get();

        if ($validos->count() !== count($ids)) {
            return back()->withInput()->withErrors([
                'actividades' => 'Selecciona actividades que pertenezcan al evento elegido.'
            ]);
        }

        // Verificar horarios cruzados
        if ($d['estado'] !== 'anulado') {
            $errorHorario = $this->validarHorarios(
                $validos,
                (int) $d['participante_id']
            );

            if ($errorHorario) {
                return back()->withInput()->withErrors([
                    'actividades' => $errorHorario
                ]);
            }
        }

        // Verificar cupos
        if ($d['estado'] !== 'anulado') {
            foreach ($validos as $a) {
                if (
                    $a->inscripciones()
                        ->where('estado', '!=', 'anulado')
                        ->count() >= $a->cupo
                ) {
                    return back()->withInput()->withErrors([
                        'actividades' => 'La actividad "' .
                            $a->nombre . '" ya alcanzó su cupo.'
                    ]);
                }
            }
        }

        // Registrar inscripción
        $inscripcion = Inscripcion::create(
            collect($d)->except('actividades')->all()
        );

        $inscripcion->actividades()->sync($ids);

        return redirect()
            ->route('inscripciones.index')
            ->with('success', 'Inscripción registrada correctamente.');
    }

    public function edit(Inscripcion $inscripcion)
    {
        $participantes = Participante::orderBy('apellido')->get();
        $eventos = Evento::with('actividades')->orderBy('nombre')->get();

        $inscripcion->load('actividades');

        return view('inscripciones.edit', compact(
            'inscripcion',
            'participantes',
            'eventos'
        ));
    }

    public function update(Request $r, Inscripcion $inscripcion)
    {
        $d = $r->validate([
            'participante_id' => 'required|exists:participantes,id',
            'evento_id' => 'required|exists:eventos,id',
            'fecha_inscripcion' => 'required|date',
            'estado' => 'required|in:inscrito,confirmado,anulado',
            'actividades' => 'array',
            'actividades.*' => 'integer|distinct|exists:actividades,id',
        ]);

        // Evitar duplicados al editar
        if (
            Inscripcion::where('participante_id', $d['participante_id'])
                ->where('evento_id', $d['evento_id'])
                ->where('id', '!=', $inscripcion->id)
                ->exists()
        ) {
            return back()->withInput()->withErrors([
                'participante_id' => 'Este participante ya está inscrito en ese evento.'
            ]);
        }

        $ids = $d['actividades'] ?? [];

        // Comprobar actividades del evento
        $validos = Actividad::where('evento_id', $d['evento_id'])
            ->whereIn('id', $ids)
            ->get();

        if ($validos->count() !== count($ids)) {
            return back()->withInput()->withErrors([
                'actividades' => 'Selecciona actividades que pertenezcan al evento elegido.'
            ]);
        }

        // Verificar horarios cruzados al editar
        if ($d['estado'] !== 'anulado') {
            $errorHorario = $this->validarHorarios(
                $validos,
                (int) $d['participante_id'],
                $inscripcion->id
            );

            if ($errorHorario) {
                return back()->withInput()->withErrors([
                    'actividades' => $errorHorario
                ]);
            }
        }

        // Verificar cupos sin contar la inscripción actual
        if ($d['estado'] !== 'anulado') {
            foreach ($validos as $a) {
                if (
                    $a->inscripciones()
                        ->where('inscripciones.id', '!=', $inscripcion->id)
                        ->where('estado', '!=', 'anulado')
                        ->count() >= $a->cupo
                ) {
                    return back()->withInput()->withErrors([
                        'actividades' => 'La actividad "' .
                            $a->nombre . '" ya alcanzó su cupo.'
                    ]);
                }
            }
        }

        // Actualizar inscripción
        $inscripcion->update(
            collect($d)->except('actividades')->all()
        );

        $inscripcion->actividades()->sync($ids);

        return redirect()
            ->route('inscripciones.index')
            ->with('success', 'Inscripción actualizada correctamente.');
    }

    public function show(Inscripcion $inscripcion)
    {
        $inscripcion->load([
            'participante',
            'evento',
            'actividades',
            'certificado'
        ]);

        return view('inscripciones.show', compact('inscripcion'));
    }

    public function destroy(Inscripcion $inscripcion)
    {
        $inscripcion->delete();

        return back()->with('success', 'Inscripción eliminada.');
    }
}
