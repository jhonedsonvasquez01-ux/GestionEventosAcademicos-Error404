
@extends('layouts.app')

@section('titulo', 'Registrar actividad')

@section('contenido')
    <h2>Registrar actividad</h2>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    @if ($eventos->isEmpty())
        <p>Primero debes registrar un evento.</p>
        <a href="{{ route('eventos.create') }}">Registrar evento</a>
    @else
        <form action="{{ route('actividades.store') }}" method="POST">
            @csrf

            <p>
                <label>Evento:</label><br>
                <select name="evento_id" required>
                    <option value="">Selecciona un evento</option>
                    @foreach ($eventos as $evento)
                        <option value="{{ $evento->id }}"
                            @selected(old('evento_id') == $evento->id)>
                            {{ $evento->nombre }}
                        </option>
                    @endforeach
                </select>
            </p>

            <p>
                <label>Nombre de la actividad:</label><br>
                <input type="text" name="nombre"
                       value="{{ old('nombre') }}" required>
            </p>

            <p>
                <label>Tipo:</label><br>
                <select name="tipo" required>
                    <option value="">Selecciona un tipo</option>
                    @foreach (['Conferencia', 'Taller', 'Panel'] as $tipo)
                        <option value="{{ $tipo }}"
                            @selected(old('tipo') == $tipo)>
                            {{ $tipo }}
                        </option>
                    @endforeach
                </select>
            </p>

            <p>
                <label>Fecha:</label><br>
                <input type="date" name="fecha"
                       value="{{ old('fecha') }}" required>
            </p>

            <p>
                <label>Hora de inicio:</label><br>
                <input type="time" name="hora_inicio"
                       value="{{ old('hora_inicio') }}" required>
            </p>

            <p>
                <label>Hora de finalización:</label><br>
                <input type="time" name="hora_fin"
                       value="{{ old('hora_fin') }}" required>
            </p>

            <p>
                <label>Sala:</label><br>
                <input type="text" name="sala"
                       value="{{ old('sala') }}" required>
            </p>

            <p>
                <label>Capacidad de participantes:</label><br>
                <input type="number" name="cupo" min="1"
                       value="{{ old('cupo') }}" required>
            </p>

            <button type="submit">Guardar actividad</button>
            <a href="{{ route('actividades.index') }}">Cancelar</a>
        </form>
    @endif
@endsection
