@extends('layouts.app')
@section('titulo','Registrar participante')
@section('contenido')
<h2>Registrar participante</h2>
<form method="POST" action="{{ route('participantes.store') }}">@csrf 
<label>Nombre</label><input type="text" name="nombre" value="{{ old('nombre') }}" required><br>
<label>Apellido</label><input type="text" name="apellido" value="{{ old('apellido') }}" required><br>
<label>CI</label><input type="text" name="ci" value="{{ old('ci') }}" required><br>
<label>Correo</label><input type="email" name="email" value="{{ old('email') }}" required><br>
<label>Teléfono</label><input type="text" name="telefono" value="{{ old('telefono') }}" ><br>
<button>Guardar</button> <a href="{{ route('participantes.index') }}">Cancelar</a></form>
@endsection
