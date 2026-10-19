
@extends('layouts.app')

@section('titulo', 'Detalle de actividad')

@section('contenido')
    <h2>Detalle de la actividad</h2>

    <p><strong>Evento:</strong> {{ $actividad->evento?->nombre ?? 'Sin evento asociado' }}</p>
    <p><strong>Nombre:</strong> {{ $actividad->nombre }}</p>
    <p><strong>Tipo:</strong> {{ $actividad->tipo }}</p>
    <p><strong>Fecha:</strong> {{ $actividad->fecha->format('d/m/Y') }}</p>
    <p><strong>Horario:</strong>
        {{ substr($actividad->hora_inicio, 0, 5) }}
        - {{ substr($actividad->hora_fin, 0, 5) }}
    </p>
    <p><strong>Sala:</strong> {{ $actividad->sala }}</p>
    <p><strong>Capacidad:</strong> {{ $actividad->cupo }} personas</p>

    <a href="{{ route('actividades.edit', $actividad) }}">Editar</a> |
    <a href="{{ route('actividades.index') }}">Volver al listado</a>
@endsection
