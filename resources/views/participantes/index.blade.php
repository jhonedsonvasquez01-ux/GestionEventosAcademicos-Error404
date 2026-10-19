@extends('layouts.app')
@section('titulo','Participantes')
@section('contenido')
<h2>Participantes</h2><p><a href="{{ route('participantes.create') }}">Registrar</a></p>
<table><thead><tr><th>Nombre</th><th>Apellido</th><th>CI</th><th>Correo</th><th>Teléfono</th><th>Acciones</th></tr></thead><tbody>
@forelse($participantes as $participante)
<tr><td>{{ $participante->nombre }}</td><td>{{ $participante->apellido }}</td><td>{{ $participante->ci }}</td><td>{{ $participante->email }}</td><td>{{ $participante->telefono }}</td><td><a href="{{ route('participantes.show', $participante) }}">Ver</a> <a href="{{ route('participantes.edit', $participante) }}">Editar</a>
<form class="inline" method="POST" action="{{ route('participantes.destroy', $participante) }}" onsubmit="return confirm('¿Eliminar?')">@csrf @method('DELETE')<button>Eliminar</button></form></td></tr>
@empty<tr><td colspan="6">No hay registros.</td></tr>@endforelse
</tbody></table>{{ $participantes->links() }}
@endsection
