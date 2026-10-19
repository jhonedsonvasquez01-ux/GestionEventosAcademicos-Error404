@extends('layouts.app')
@section('titulo', 'Certificado')
@section('contenido')
<h2>Certificado de participación</h2>

<p>
    Se certifica que <strong>{{ $certificado->inscripcion->participante->nombre }} {{ $certificado->inscripcion->participante->apellido }}</strong> 
    participó en el evento <strong>{{ $certificado->inscripcion->evento->nombre }}</strong>.
</p>

<p><b>Código:</b> {{ $certificado->codigo }}</p>
<p><b>Fecha de emisión:</b> {{ $certificado->fecha_emision->format('d/m/Y') }}</p>

<button onclick="window.print()">Imprimir certificado</button> 
<a href="{{ route('certificados.index') }}">Volver</a>
@endsection