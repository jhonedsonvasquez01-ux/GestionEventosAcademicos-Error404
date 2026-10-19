@extends('layouts.app')
@section('titulo','Inscripciones')
@section('contenido')
<h2>Inscripciones</h2><p><a href="{{ route('inscripciones.create') }}">Registrar inscripción</a></p>
<table><tr><th>Participante</th><th>Evento</th><th>Fecha</th><th>Estado</th><th>Actividades</th><th>Acciones</th></tr>
@forelse($inscripciones as $i)<tr><td>{{ $i->participante->nombre }} {{ $i->participante->apellido }}</td><td>{{ $i->evento->nombre }}</td><td>{{ $i->fecha_inscripcion->format('d/m/Y') }}</td><td>{{ $i->estado }}</td><td>{{ $i->actividades->pluck('nombre')->join(', ') }}</td><td><a href="{{ route('inscripciones.show',$i) }}">Ver</a> <a href="{{ route('inscripciones.edit',$i) }}">Editar</a><form class="inline" method="POST" action="{{ route('inscripciones.destroy',$i) }}">@csrf @method('DELETE')<button>Eliminar</button></form></td></tr>
@empty<tr><td colspan="6">No hay inscripciones.</td></tr>@endforelse</table>{{ $inscripciones->links() }}
@endsection
