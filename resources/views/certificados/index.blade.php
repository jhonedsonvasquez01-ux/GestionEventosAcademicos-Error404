@extends('layouts.app')
@section('titulo','Certificados')
@section('contenido')
<h2>Certificados</h2><p>Se puede emitir un certificado cuando el participante tiene al menos 80% de asistencia en las actividades seleccionadas en su inscripción.</p>
<h3>Inscripciones elegibles</h3>
@if($elegibles->isEmpty())<p>No hay inscripciones que cumplan el requisito por ahora.</p>@else
<table><tr><th>Participante</th><th>Evento</th><th>Asistencia</th><th>Acción</th></tr>
@foreach($elegibles as $i)@php $total=$i->actividades->count(); $presentes=$i->asistencias->where('asistio',true)->whereIn('actividad_id',$i->actividades->pluck('id'))->count(); @endphp
<tr><td>{{ $i->participante->nombre }} {{ $i->participante->apellido }}</td><td>{{ $i->evento->nombre }}</td><td>{{ $presentes }}/{{ $total }} ({{ round($presentes/$total*100) }}%)</td><td><form method="POST" action="{{ route('certificados.store') }}">@csrf<input type="hidden" name="inscripcion_id" value="{{ $i->id }}"><button>Emitir certificado</button></form></td></tr>
@endforeach</table>@endif
<h3>Certificados emitidos</h3><table><tr><th>Código</th><th>Participante</th><th>Evento</th><th>Fecha</th><th>Acciones</th></tr>
@forelse($certificados as $c)<tr><td>{{ $c->codigo }}</td><td>{{ $c->inscripcion->participante->nombre }} {{ $c->inscripcion->participante->apellido }}</td><td>{{ $c->inscripcion->evento->nombre }}</td><td>{{ $c->fecha_emision->format('d/m/Y') }}</td><td><a href="{{ route('certificados.show',$c) }}">Ver</a> <form class="inline" method="POST" action="{{ route('certificados.destroy',$c) }}">@csrf @method('DELETE')<button>Eliminar</button></form></td></tr>
@empty<tr><td colspan="5">No hay certificados emitidos.</td></tr>@endforelse</table>{{ $certificados->links() }}
@endsection
