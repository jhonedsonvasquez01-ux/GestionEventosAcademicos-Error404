<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Http\Requests\EventoRequest;

class EventoController extends Controller
{
    public function index()
    {
        $eventos = Evento::orderBy('fecha_inicio', 'desc')
            ->paginate(10);

        return view('eventos.index', compact('eventos'));
    }

    public function create()
    {
        return view('eventos.create');
    }

    public function store(EventoRequest $request)
    {
        Evento::create($request->validated());

        return redirect()
            ->route('eventos.index')
            ->with('success', 'Evento registrado correctamente.');
    }

    public function show(Evento $evento)
    {
        return view('eventos.show', compact('evento'));
    }

    public function edit(Evento $evento)
    {
        return view('eventos.edit', compact('evento'));
    }

    public function update(EventoRequest $request, Evento $evento)
    {
        $evento->update($request->validated());

        return redirect()
            ->route('eventos.index')
            ->with('success', 'Evento actualizado correctamente.');
    }

    public function destroy(Evento $evento)
    {
        $evento->delete();

        return redirect()
            ->route('eventos.index')
            ->with('success', 'Evento eliminado correctamente.');
    }
}
