
@extends('layouts.app')

@section('titulo', 'Editar evento')

@section('contenido')
    <h2>Editar evento académico</h2>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('eventos.update', $evento) }}" method="POST">
        @csrf
        @method('PUT')

        <p>
            <label>Nombre:</label><br>
            <input type="text" name="nombre"
                   value="{{ old('nombre', $evento->nombre) }}" required>
        </p>

        <p>
            <label>Fecha de inicio:</label><br>
            <input type="date" name="fecha_inicio"
                   value="{{ old('fecha_inicio', $evento->fecha_inicio->format('Y-m-d')) }}" required>
        </p>

        <p>
            <label>Fecha de finalización:</label><br>
            <input type="date" name="fecha_fin"
                   value="{{ old('fecha_fin', $evento->fecha_fin->format('Y-m-d')) }}" required>
        </p>

        <p>
            <label>Lugar:</label><br>
            <input type="text" name="lugar"
                   value="{{ old('lugar', $evento->lugar) }}" required>
        </p>

        <p>
            <label>Costo de inscripción (Bs):</label><br>
            <input type="number" name="costo_inscripcion"
                   value="{{ old('costo_inscripcion', $evento->costo_inscripcion) }}"
                   min="0" step="0.01" required>
        </p>

        <button type="submit">Actualizar evento</button>
        <a href="{{ route('eventos.index') }}">Cancelar</a>
    </form>
@endsection
