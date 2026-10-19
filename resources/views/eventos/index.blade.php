
@extends('layouts.app')

@section('titulo', 'Eventos')

@section('contenido')
    <h2>Gestión de eventos</h2>

    <p>Desde aquí puedes administrar los eventos académicos.</p>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <a href="{{ route('eventos.create') }}">Registrar nuevo evento</a>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Fecha de inicio</th>
                <th>Fecha de fin</th>
                <th>Lugar</th>
                <th>Costo (Bs)</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($eventos as $evento)
                <tr>
                    <td>{{ $evento->nombre }}</td>
                    <td>{{ $evento->fecha_inicio->format('d/m/Y') }}</td>
                    <td>{{ $evento->fecha_fin->format('d/m/Y') }}</td>
                    <td>{{ $evento->lugar }}</td>
                    <td>{{ $evento->costo_inscripcion }}</td>
                    <td>
                        <a href="{{ route('eventos.show', $evento) }}">Ver</a> |
                        <a href="{{ route('eventos.edit', $evento) }}">Editar</a> |

                        <form action="{{ route('eventos.destroy', $evento) }}"
                              method="POST"
                              style="display:inline"
                              onsubmit="return confirm('¿Eliminar este evento?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No hay eventos registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $eventos->links() }}
@endsection
