@extends('layouts.app')
@section('contenido')
<h2>Detalle de expositor</h2>
<p><strong>Nombre:</strong> {{ $expositor->nombre }}}</p><p><strong>Apellido:</strong> {{ $expositor->apellido }}}</p><p><strong>Especialidad:</strong> {{ $expositor->especialidad }}}</p><p><strong>Institución:</strong> {{ $expositor->institucion }}}</p><p><strong>Correo:</strong> {{ $expositor->email }}}</p>
<a href="{{ route('expositores.index') }}">Volver</a>
@endsection
