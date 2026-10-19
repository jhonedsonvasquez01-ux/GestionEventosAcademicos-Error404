<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\Asistencia;
use App\Models\Inscripcion;
use Illuminate\Http\Request;

class AsistenciaController extends Controller
{
    public function index()
    {
        $actividades = Actividad::with('evento')->orderBy('fecha')->get();
        $inscripciones = Inscripcion::with(['participante', 'actividades'])->where('estado', '!=', 'anulado')->orderBy('id', 'desc')->get();
        $asistencias = Asistencia::with(['inscripcion.participante', 'actividad'])->latest()->paginate(20);

        return view('asistencias.index', compact('actividades', 'inscripciones', 'asistencias'));
    }

    public function store(Request $r)
    {
        $d = $r->validate([
            'actividad_id' => 'required|exists:actividades,id',
            'inscripcion_id' => 'required|exists:inscripciones,id',
            'asistio' => 'required|boolean',
        ]);

        $actividad = Actividad::findOrFail($d['actividad_id']);
        $inscripcion = Inscripcion::with('actividades')->findOrFail($d['inscripcion_id']);

        if (!$inscripcion->actividades->contains('id', $actividad->id)) {
            return back()->withErrors(['inscripcion_id' => 'La inscripción no incluye esta actividad.'])->withInput();
        }

        Asistencia::updateOrCreate(
            [
                'inscripcion_id' => $inscripcion->id,
                'actividad_id' => $actividad->id,
            ],
            [
                'asistio' => $d['asistio'],
                'fecha_hora_registro' => $d['asistio'] ? now() : null,
            ]
        );

        return back()->with('success', 'Asistencia guardada.');
    }
}