@extends('layouts.app')

@section('contenido')
<h2>Detalle de inscripción</h2>

<p><b>Participante:</b> {{ $inscripcion->participante->nombre }} {{ $inscripcion->participante->apellido }}</p>
<p><b>Evento:</b> {{ $inscripcion->evento->nombre }}</p>
<p><b>Estado:</b> {{ $inscripcion->estado }}</p>
<p><b>Actividades:</b> {{ $inscripcion->actividades->pluck('nombre')->join(', ') }}</p>

<a href="{{ route('inscripciones.index') }}">Volver</a>
@endsection