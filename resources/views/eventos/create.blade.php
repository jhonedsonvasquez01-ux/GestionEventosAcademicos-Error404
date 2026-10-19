
@extends('layouts.app')

@section('titulo', 'Registrar evento')

@section('contenido')
    <h2>Registrar evento académico</h2>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('eventos.store') }}" method="POST">
        @csrf

        <p>
            <label>Nombre:</label><br>
            <input type="text" name="nombre" value="{{ old('nombre') }}" required>
        </p>

        <p>
            <label>Fecha de inicio:</label><br>
            <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio') }}" required>
        </p>

        <p>
            <label>Fecha de finalización:</label><br>
            <input type="date" name="fecha_fin" value="{{ old('fecha_fin') }}" required>
        </p>

        <p>
            <label>Lugar:</label><br>
            <input type="text" name="lugar" value="{{ old('lugar') }}" required>
        </p>

        <p>
            <label>Costo de inscripción (Bs):</label><br>
            <input type="number" name="costo_inscripcion"
                   value="{{ old('costo_inscripcion', '0.00') }}"
                   min="0" step="0.01" required>
        </p>

        <button type="submit">Guardar evento</button>
        <a href="{{ route('eventos.index') }}">Cancelar</a>
    </form>
@endsection

