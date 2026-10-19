@extends('layouts.app')
@section('titulo','Editar participante')
@section('contenido')
<h2>Editar participante</h2>
<form method="POST" action="{{ route('participantes.update', $participante) }}">@csrf @method("PUT")
<label>Nombre</label><input type="text" name="nombre" value="{{ old('nombre', $participante->nombre) }}" required><br>
<label>Apellido</label><input type="text" name="apellido" value="{{ old('apellido', $participante->apellido) }}" required><br>
<label>CI</label><input type="text" name="ci" value="{{ old('ci', $participante->ci) }}" required><br>
<label>Correo</label><input type="email" name="email" value="{{ old('email', $participante->email) }}" required><br>
<label>Teléfono</label><input type="text" name="telefono" value="{{ old('telefono', $participante->telefono) }}" ><br>
<button>Guardar</button> <a href="{{ route('participantes.index') }}">Cancelar</a></form>
@endsection
