@extends('layouts.app')
@section('titulo', 'Editar expositor')
@section('contenido')
<h2>Editar expositor</h2>

<form method="POST" action="{{ route('expositores.update', $expositor) }}">
    @csrf 
    @method("PUT")
    
    <label>Nombre</label>
    <input type="text" name="nombre" value="{{ old('nombre', $expositor->nombre) }}" required><br>

    <label>Apellido</label>
    <input type="text" name="apellido" value="{{ old('apellido', $expositor->apellido) }}" required><br>

    <label>Especialidad</label>
    <input type="text" name="especialidad" value="{{ old('especialidad', $expositor->especialidad) }}"><br>

    <label>Institución</label>
    <input type="text" name="institucion" value="{{ old('institucion', $expositor->institucion) }}"><br>

    <label>Correo</label>
    <input type="email" name="email" value="{{ old('email', $expositor->email) }}"><br>

    <button>Guardar</button> 
    <a href="{{ route('expositores.index') }}">Cancelar</a>
</form>
@endsection