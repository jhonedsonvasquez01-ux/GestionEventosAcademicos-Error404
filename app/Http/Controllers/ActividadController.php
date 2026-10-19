<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\Evento;
use App\Http\Requests\ActividadRequest;
use Illuminate\Http\Request;

class ActividadController extends Controller
{
    public function index()
    {
        $actividades = Actividad::with('evento')
            ->orderBy('fecha')
            ->orderBy('hora_inicio')
            ->paginate(10);

        return view('actividades.index', compact('actividades'));
    }

    public function create()
    {
        $eventos = Evento::orderBy('nombre')->get();

        return view('actividades.create', compact('eventos'));
    }

    public function store(ActividadRequest $request)
    {
        $datos = $request->validated();

        $conflicto = Actividad::where('evento_id', $datos['evento_id'])
            ->where('fecha', $datos['fecha'])
            ->where('sala', $datos['sala'])
            ->where('hora_inicio', '<', $datos['hora_fin'])
            ->where('hora_fin', '>', $datos['hora_inicio'])
            ->exists();

        if ($conflicto) {
            return back()
                ->withInput()
                ->withErrors([
                    'hora_inicio' => 'Ya existe una actividad en esa sala y horario.',
                ]);
        }

        Actividad::create($datos);

        return redirect()
            ->route('actividades.index')
            ->with('success', 'Actividad registrada correctamente.');
    }

    public function show(Actividad $actividad)
    {
        $actividad->load('evento');

        return view('actividades.show', compact('actividad'));
    }

    public function edit(Actividad $actividad)
    {
        $eventos = Evento::orderBy('nombre')->get();

        return view('actividades.edit', compact('actividad', 'eventos'));
    }

    public function update(ActividadRequest $request, Actividad $actividad)
    {
        $datos = $request->validated();

        $conflicto = Actividad::where('evento_id', $datos['evento_id'])
            ->where('fecha', $datos['fecha'])
            ->where('sala', $datos['sala'])
            ->where('id', '!=', $actividad->id)
            ->where('hora_inicio', '<', $datos['hora_fin'])
            ->where('hora_fin', '>', $datos['hora_inicio'])
            ->exists();

        if ($conflicto) {
            return back()
                ->withInput()
                ->withErrors([
                    'hora_inicio' => 'Ya existe una actividad en esa sala y horario.',
                ]);
        }

        $actividad->update($datos);

        return redirect()
            ->route('actividades.index')
            ->with('success', 'Actividad actualizada correctamente.');
    }

    public function destroy(Actividad $actividad)
    {
        $actividad->delete();

        return redirect()
            ->route('actividades.index')
            ->with('success', 'Actividad eliminada correctamente.');
    }
}
