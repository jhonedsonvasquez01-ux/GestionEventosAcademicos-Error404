@extends('layouts.app')
@section('titulo', 'Expositores')
@section('contenido')
<h2>Expositores</h2>
<p><a href="{{ route('expositores.create') }}">Registrar</a></p>

<table>
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Especialidad</th>
            <th>Institución</th>
            <th>Correo</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($expositores as $expositor)
            <tr>
                <td>{{ $expositor->nombre }}</td>
                <td>{{ $expositor->apellido }}</td>
                <td>{{ $expositor->especialidad }}</td>
                <td>{{ $expositor->institucion }}</td>
                <td>{{ $expositor->email }}</td>
                <td>
                    <a href="{{ route('expositores.show', $expositor) }}">Ver</a>
                    <a href="{{ route('expositores.edit', $expositor) }}">Editar</a>
                    <form class="inline" method="POST" action="{{ route('expositores.destroy', $expositor) }}" onsubmit="return confirm('¿Eliminar?')">
                        @csrf
                        @method('DELETE')
                        <button>Eliminar</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6">No hay registros.</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $expositores->links() }}
@endsection