
@extends('layouts.app')

@section('titulo', 'Detalle del evento')

@section('contenido')
    <h2>Detalle del evento académico</h2>

    <p><strong>Nombre:</strong> {{ $evento->nombre }}</p>
    <p><strong>Fecha de inicio:</strong> {{ $evento->fecha_inicio->format('d/m/Y') }}</p>
    <p><strong>Fecha de finalización:</strong> {{ $evento->fecha_fin->format('d/m/Y') }}</p>
    <p><strong>Lugar:</strong> {{ $evento->lugar }}</p>
    <p><strong>Costo de inscripción:</strong> Bs {{ $evento->costo_inscripcion }}</p>

    <a href="{{ route('eventos.edit', $evento) }}">Editar evento</a>
    |
    <a href="{{ route('eventos.index') }}">Volver al listado</a>
@endsection
