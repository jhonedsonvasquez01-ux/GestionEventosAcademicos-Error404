@extends('layouts.app')
@section('titulo','Registrar inscripción')
@section('contenido')
<h2>Registrar inscripción</h2>
<form method="POST" action="{{ route('inscripciones.store') }}">@csrf 
<label>Participante</label><select name="participante_id" required><option value="">Selecciona</option>@foreach($participantes as $p)<option value="{{ $p->id }}" @selected(old('participante_id', null) == $p->id)>{{ $p->apellido }}, {{ $p->nombre }} (CI {{ $p->ci }})</option>@endforeach</select>
<label>Evento</label><select name="evento_id" required><option value="">Selecciona</option>@foreach($eventos as $e)<option value="{{ $e->id }}" @selected(old('evento_id', null) == $e->id)>{{ $e->nombre }}</option>@endforeach</select>
<label>Fecha de inscripción</label><input type="date" name="fecha_inscripcion" value="{{ old('fecha_inscripcion', now()->toDateString()) }}" required>
<label>Estado</label><select name="estado" required>@foreach(['inscrito'=>'Inscrito','confirmado'=>'Confirmado','anulado'=>'Anulado'] as $v=>$l)<option value="{{ $v }}" @selected(old('estado', 'inscrito') == $v)>{{ $l }}</option>@endforeach</select>
<h3>Actividades del evento</h3><p>Marca las actividades a las que asistirá. No se permite superar el cupo.</p>
@foreach($eventos as $e)<fieldset><legend>{{ $e->nombre }}</legend>@foreach($e->actividades as $a)<label><input style="width:auto" type="checkbox" name="actividades[]" value="{{ $a->id }}" data-evento="{{ $e->id }}" @checked(in_array($a->id, old('actividades', [])))> {{ $a->nombre }} — {{ $a->fecha->format('d/m/Y') }} — cupo {{ $a->cupo }}</label>@endforeach</fieldset>@endforeach
<button>Guardar</button> <a href="{{ route('inscripciones.index') }}">Cancelar</a></form>
<script>const ev=document.querySelector('[name="evento_id"]');function filtrar(){document.querySelectorAll('input[name="actividades[]"]').forEach(c=>{c.disabled=c.dataset.evento!==ev.value;if(c.disabled)c.checked=false;});}ev.addEventListener('change',filtrar);filtrar();</script>
@endsection
