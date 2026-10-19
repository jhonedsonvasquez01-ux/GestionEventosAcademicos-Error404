@extends('layouts.app')
@section('contenido')
<h2>Detalle de participante</h2>
<p><strong>Nombre:</strong> {{ $participante->nombre }}}</p><p><strong>Apellido:</strong> {{ $participante->apellido }}}</p><p><strong>CI:</strong> {{ $participante->ci }}}</p><p><strong>Correo:</strong> {{ $participante->email }}}</p><p><strong>Teléfono:</strong> {{ $participante->telefono }}}</p>
<a href="{{ route('participantes.index') }}">Volver</a>
@endsection
