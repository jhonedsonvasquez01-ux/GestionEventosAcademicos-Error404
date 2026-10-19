<?php

namespace App\Http\Controllers;

use App\Models\Certificado;
use App\Models\Inscripcion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CertificadoController extends Controller
{
    public function index()
    {
        $certificados = Certificado::with('inscripcion.participante', 'inscripcion.evento')->latest()->paginate(10);
        $inscripciones = Inscripcion::with(['participante', 'evento', 'actividades', 'asistencias'])->whereDoesntHave('certificado')->where('estado', '!=', 'anulado')->get();

        $elegibles = $inscripciones->filter(function ($i) {
            $total = $i->actividades->count();
            if (!$total) {
                return false;
            }
            $presentes = $i->asistencias->where('asistio', true)->whereIn('actividad_id', $i->actividades->pluck('id'))->count();
            return ($presentes / $total) * 100 >= 80;
        });

        return view('certificados.index', compact('certificados', 'elegibles'));
    }

    public function store(Request $r)
    {
        $d = $r->validate([
            'inscripcion_id' => 'required|exists:inscripciones,id',
        ]);

        $i = Inscripcion::with(['actividades', 'asistencias', 'certificado'])->findOrFail($d['inscripcion_id']);

        if ($i->certificado) {
            return back()->withErrors(['inscripcion_id' => 'Esta inscripción ya tiene certificado.']);
        }

        $total = $i->actividades->count();
        $presentes = $i->asistencias->where('asistio', true)->whereIn('actividad_id', $i->actividades->pluck('id'))->count();

        if ($total === 0 || ($presentes / $total) * 100 < 80) {
            return back()->withErrors(['inscripcion_id' => 'No cumple el mínimo de 80% de asistencia.']);
        }

        Certificado::create([
            'inscripcion_id' => $i->id,
            'codigo' => 'CERT-' . strtoupper(Str::random(10)),
            'fecha_emision' => now()->toDateString(),
        ]);

        return redirect()->route('certificados.index')->with('success', 'Certificado emitido correctamente.');
    }

    public function show(Certificado $certificado)
    {
        $certificado->load('inscripcion.participante', 'inscripcion.evento');
        return view('certificados.show', compact('certificado'));
    }

    public function destroy(Certificado $certificado)
    {
        $certificado->delete();
        return back()->with('success', 'Certificado eliminado.');
    }
}