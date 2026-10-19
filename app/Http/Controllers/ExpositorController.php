<?php

namespace App\Http\Controllers;

use App\Models\Expositor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExpositorController extends Controller
{
    public function index()
    {
        $expositores = Expositor::orderBy('apellido')->paginate(10);
        return view('expositores.index', compact('expositores'));
    }

    public function create()
    {
        return view('expositores.create');
    }

    public function store(Request $r)
    {
        $d = $r->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'especialidad' => 'nullable|string|max:150',
            'institucion' => 'nullable|string|max:150',
            'email' => 'nullable|email|unique:expositores,email',
        ]);

        Expositor::create($d);

        return redirect()->route('expositores.index')->with('success', 'Expositor registrado.');
    }

    public function edit(Expositor $expositor)
    {
        return view('expositores.edit', compact('expositor'));
    }

    public function update(Request $r, Expositor $expositor)
    {
        $d = $r->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'especialidad' => 'nullable|string|max:150',
            'institucion' => 'nullable|string|max:150',
            'email' => [
                'nullable',
                'email',
                Rule::unique('expositores', 'email')->ignore($expositor->id),
            ],
        ]);

        $expositor->update($d);

        return redirect()->route('expositores.index')->with('success', 'Expositor actualizado.');
    }

    public function show(Expositor $expositor)
    {
        $expositor->load('actividades');
        return view('expositores.show', compact('expositor'));
    }

    public function destroy(Expositor $expositor)
    {
        $expositor->delete();
        return back()->with('success', 'Expositor eliminado.');
    }
}