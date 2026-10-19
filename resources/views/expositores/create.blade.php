@extends('layouts.app')
@section('titulo','Registrar expositor')
@section('contenido')
<h2>Registrar expositor</h2>
<form method="POST" action="{{ route('expositores.store') }}">@csrf 
<label>Nombre</label><input type="text" name="nombre" value="{{ old('nombre') }}" required><br>
<label>Apellido</label><input type="text" name="apellido" value="{{ old('apellido') }}" required><br>
<label>Especialidad</label><input type="text" name="especialidad" value="{{ old('especialidad') }}" ><br>
<label>Institución</label><input type="text" name="institucion" value="{{ old('institucion') }}" ><br>
<label>Correo</label><input type="email" name="email" value="{{ old('email') }}" ><br>
<button>Guardar</button> <a href="{{ route('expositores.index') }}">Cancelar</a></form>
@endsection
