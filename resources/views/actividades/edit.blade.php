
@extends('layouts.app')

@section('titulo', 'Editar actividad')

@section('contenido')
    <h2>Editar actividad</h2>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('actividades.update', $actividad) }}" method="POST">
        @csrf
        @method('PUT')

        <p>
            <label>Evento:</label><br>
            <select name="evento_id" required>
                @foreach ($eventos as $evento)
                    <option value="{{ $evento->id }}"
                        @selected(old('evento_id', $actividad->evento_id) == $evento->id)>
                        {{ $evento->nombre }}
                    </option>
                @endforeach
            </select>
        </p>

        <p>
            <label>Nombre:</label><br>
            <input type="text" name="nombre"
                   value="{{ old('nombre', $actividad->nombre) }}" required>
        </p>

        <p>
            <label>Tipo:</label><br>
            <select name="tipo" required>
                @foreach (['Conferencia', 'Taller', 'Panel'] as $tipo)
                    <option value="{{ $tipo }}"
                        @selected(old('tipo', $actividad->tipo) == $tipo)>
                        {{ $tipo }}
                    </option>
                @endforeach
            </select>
        </p>

        <p>
            <label>Fecha:</label><br>
            <input type="date" name="fecha"
                   value="{{ old('fecha', $actividad->fecha->format('Y-m-d')) }}" required>
        </p>

        <p>
            <label>Hora de inicio:</label><br>
            <input type="time" name="hora_inicio"
                   value="{{ old('hora_inicio', substr($actividad->hora_inicio, 0, 5)) }}" required>
        </p>

        <p>
            <label>Hora de finalización:</label><br>
            <input type="time" name="hora_fin"
                   value="{{ old('hora_fin', substr($actividad->hora_fin, 0, 5)) }}" required>
        </p>

        <p>
            <label>Sala:</label><br>
            <input type="text" name="sala"
                   value="{{ old('sala', $actividad->sala) }}" required>
        </p>

        <p>
            <label>Capacidad:</label><br>
            <input type="number" name="cupo" min="1"
                   value="{{ old('cupo', $actividad->cupo) }}" required>
        </p>

        <button type="submit">Actualizar actividad</button>
        <a href="{{ route('actividades.index') }}">Cancelar</a>
    </form>
@endsection
