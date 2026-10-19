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
        if (Inscripcion::where('participante_id', $d['participante_id'])
            ->where('evento_id', $d['evento_id'])
            ->exists()) {

            return back()->withInput()->withErrors([
                'participante_id' => 'Este participante ya está inscrito en ese evento.'
            ]);
        }

        $ids = $d['actividades'] ?? [];

        // Verificar que las actividades pertenezcan al evento
        $validos = Actividad::where('evento_id', $d['evento_id'])
            ->whereIn('id', $ids)
            ->get();

        if ($validos->count() != count($ids)) {
            return back()->withInput()->withErrors([
                'actividades' => 'Selecciona actividades que pertenezcan al evento elegido.'
            ]);
        }

        // Validar horarios cruzados entre actividades seleccionadas
        foreach ($validos as $actividad1) {
            foreach ($validos as $actividad2) {

                if ($actividad1->id >= $actividad2->id) {
                    continue;
                }

                if (
                    $actividad1->fecha == $actividad2->fecha &&
                    $actividad1->hora_inicio < $actividad2->hora_fin &&
                    $actividad2->hora_inicio < $actividad1->hora_fin
                ) {
                    return back()->withInput()->withErrors([
                        'actividades' => 'Las actividades "' .
                            $actividad1->nombre . '" y "' .
                            $actividad2->nombre .
                            '" tienen horarios cruzados.'
                    ]);
                }
            }
        }

        // Validar cupos disponibles
        foreach ($validos as $a) {
            if ($a->inscripciones()->count() >= $a->cupo) {
                return back()->withInput()->withErrors([
                    'actividades' => 'La actividad ' . $a->nombre .
                        ' ya alcanzó su cupo.'
                ]);
            }
        }

        // Registrar inscripción
        $i = Inscripcion::create(
            collect($d)->except('actividades')->all()
        );

        $i->actividades()->sync($ids);

        return redirect()
            ->route('inscripciones.index')
            ->with('success', 'Inscripción registrada.');
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

        // Evitar inscripciones duplicadas
        if (Inscripcion::where('participante_id', $d['participante_id'])
            ->where('evento_id', $d['evento_id'])
            ->where('id', '!=', $inscripcion->id)
            ->exists()) {

            return back()->withInput()->withErrors([
                'participante_id' => 'Este participante ya está inscrito en ese evento.'
            ]);
        }

        $ids = $d['actividades'] ?? [];

        // Verificar que las actividades pertenezcan al evento
        $validos = Actividad::where('evento_id', $d['evento_id'])
            ->whereIn('id', $ids)
            ->get();

        if ($validos->count() != count($ids)) {
            return back()->withInput()->withErrors([
                'actividades' => 'Selecciona actividades que pertenezcan al evento elegido.'
            ]);
        }

        // Validar horarios cruzados
        foreach ($validos as $actividad1) {
            foreach ($validos as $actividad2) {

                if ($actividad1->id >= $actividad2->id) {
                    continue;
                }

                if (
                    $actividad1->fecha == $actividad2->fecha &&
                    $actividad1->hora_inicio < $actividad2->hora_fin &&
                    $actividad2->hora_inicio < $actividad1->hora_fin
                ) {
                    return back()->withInput()->withErrors([
                        'actividades' => 'Las actividades "' .
                            $actividad1->nombre . '" y "' .
                            $actividad2->nombre .
                            '" tienen horarios cruzados.'
                    ]);
                }
            }
        }

        // Validar cupos sin contar la inscripción actual
        foreach ($validos as $a) {
            if (
                $a->inscripciones()
                    ->where('inscripcion_id', '!=', $inscripcion->id)
                    ->count() >= $a->cupo
            ) {
                return back()->withInput()->withErrors([
                    'actividades' => 'La actividad ' . $a->nombre .
                        ' ya alcanzó su cupo.'
                ]);
            }
        }

        // Actualizar inscripción
        $inscripcion->update(
            collect($d)->except('actividades')->all()
        );

        $inscripcion->actividades()->sync($ids);

        return redirect()
            ->route('inscripciones.index')
            ->with('success', 'Inscripción actualizada.');
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
