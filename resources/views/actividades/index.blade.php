
@extends('layouts.app')

@section('titulo', 'Actividades')

@section('contenido')
    <h2>Gestión de actividades</h2>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <p>
        <a href="{{ route('actividades.create') }}">
            Registrar nueva actividad
        </a>
    </p>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Actividad</th>
                <th>Evento</th>
                <th>Tipo</th>
                <th>Fecha</th>
                <th>Horario</th>
                <th>Sala</th>
                <th>Cupo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($actividades as $actividad)
                <tr>
                    <td>{{ $actividad->nombre }}</td>
                    <td>{{ $actividad->evento->nombre }}</td>
                    <td>{{ $actividad->tipo }}</td>
                    <td>{{ $actividad->fecha->format('d/m/Y') }}</td>
                    <td>{{ substr($actividad->hora_inicio, 0, 5) }}
                        - {{ substr($actividad->hora_fin, 0, 5) }}</td>
                    <td>{{ $actividad->sala }}</td>
                    <td>{{ $actividad->cupo }}</td>
                    <td>
                        <a href="{{ route('actividades.show', $actividad) }}">Ver</a> |
                        <a href="{{ route('actividades.edit', $actividad) }}">Editar</a> |

                        <form action="{{ route('actividades.destroy', $actividad) }}"
                              method="POST" style="display:inline"
                              onsubmit="return confirm('¿Eliminar esta actividad?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">No hay actividades registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $actividades->links() }}
@endsection
