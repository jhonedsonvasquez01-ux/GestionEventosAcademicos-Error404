@extends('layouts.app')
@section('titulo','Asistencias')
@section('contenido')
<h2>Registro de asistencias</h2>
<form method="POST" action="{{ route('asistencias.store') }}">@csrf
<label>Actividad</label><select name="actividad_id" required><option value="">Selecciona actividad</option>@foreach($actividades as $a)<option value="{{ $a->id }}" @selected(old('actividad_id')==$a->id)>{{ $a->evento->nombre }} — {{ $a->nombre }} ({{ $a->fecha->format('d/m/Y') }})</option>@endforeach</select>
<label>Inscripción (solo se aceptará si incluye la actividad seleccionada)</label><select name="inscripcion_id" required><option value="">Selecciona participante</option>@foreach($inscripciones as $i)<option value="{{ $i->id }}" @selected(old('inscripcion_id')==$i->id)>{{ $i->participante->apellido }}, {{ $i->participante->nombre }} — {{ $i->evento->nombre ?? '' }}</option>@endforeach</select>
<label>Asistió</label><select name="asistio" required><option value="1">Sí, asistió</option><option value="0">No asistió</option></select><p><button>Guardar asistencia</button></p></form>
<h3>Asistencias registradas</h3><table><tr><th>Participante</th><th>Actividad</th><th>Asistió</th><th>Fecha de registro</th></tr>
@forelse($asistencias as $a)<tr><td>{{ $a->inscripcion->participante->nombre }} {{ $a->inscripcion->participante->apellido }}</td><td>{{ $a->actividad->nombre }}</td><td>{{ $a->asistio ? 'Sí' : 'No' }}</td><td>{{ $a->fecha_hora_registro?->format('d/m/Y H:i') ?? '—' }}</td></tr>
@empty<tr><td colspan="4">No hay asistencias registradas.</td></tr>@endforelse</table>{{ $asistencias->links() }}
@endsection
