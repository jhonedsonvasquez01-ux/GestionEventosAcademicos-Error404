
@extends('layouts.app')

@section('titulo', 'Asistencias')

@section('contenido')

<style>
    /* PAGINACIÓN PERSONALIZADA DE ASISTENCIAS */

    .paginacion-asistencias {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        gap: 5px;
        margin: 25px 0;
    }

    .paginacion-asistencias a,
    .paginacion-asistencias span {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        min-width: 36px;
        height: 36px;
        padding: 0 10px;
        border-radius: 5px;
        background: #14253e;
        color: #ffffff;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        box-sizing: border-box;
    }

    .paginacion-asistencias a:hover {
        background: #2563a6;
        color: #ffffff;
    }

    .paginacion-asistencias .activa {
        background: #2563a6;
        color: #ffffff;
    }

    .paginacion-asistencias .deshabilitada {
        background: #dce2e9;
        color: #8490a0;
        cursor: not-allowed;
    }

    .paginacion-asistencias .puntos {
        background: transparent;
        color: #475569;
    }
</style>

<h2>Registro de asistencias</h2>

<form method="POST" action="{{ route('asistencias.store') }}">
    @csrf

    <label>Actividad</label>

    <select name="actividad_id" required>
        <option value="">Selecciona actividad</option>

        @foreach($actividades as $a)
            <option
                value="{{ $a->id }}"
                @selected(old('actividad_id') == $a->id)
            >
                {{ $a->evento->nombre }}
                —
                {{ $a->nombre }}
                ({{ $a->fecha->format('d/m/Y') }})
            </option>
        @endforeach
    </select>

    <label>
        Inscripción (solo se aceptará si incluye la actividad seleccionada)
    </label>

    <select name="inscripcion_id" required>
        <option value="">Selecciona participante</option>

        @foreach($inscripciones as $i)
            <option
                value="{{ $i->id }}"
                @selected(old('inscripcion_id') == $i->id)
            >
                {{ $i->participante->apellido }},
                {{ $i->participante->nombre }}
                —
                {{ $i->evento->nombre ?? '' }}
            </option>
        @endforeach
    </select>

    <label>Asistió</label>

    <select name="asistio" required>
        <option value="1" @selected(old('asistio', '1') == '1')>
            Sí, asistió
        </option>

        <option value="0" @selected(old('asistio') == '0')>
            No asistió
        </option>
    </select>

    <p>
        <button type="submit">
            Guardar asistencia
        </button>
    </p>
</form>

<h3>Asistencias registradas</h3>

<table>
    <tr>
        <th>Participante</th>
        <th>Actividad</th>
        <th>Asistió</th>
        <th>Fecha de registro</th>
    </tr>

    @forelse($asistencias as $a)
        <tr>
            <td>
                {{ $a->inscripcion->participante->nombre }}
                {{ $a->inscripcion->participante->apellido }}
            </td>

            <td>
                {{ $a->actividad->nombre }}
            </td>

            <td>
                {{ $a->asistio ? 'Sí' : 'No' }}
            </td>

            <td>
                {{ $a->fecha_hora_registro?->format('d/m/Y H:i') ?? '—' }}
            </td>
        </tr>

    @empty
        <tr>
            <td colspan="4">
                No hay asistencias registradas.
            </td>
        </tr>
    @endforelse
</table>

{{-- PAGINACIÓN PERSONALIZADA --}}

@if ($asistencias->hasPages())

    @php
        $actual = $asistencias->currentPage();
        $ultima = $asistencias->lastPage();

        $inicio = max(1, $actual - 2);
        $fin = min($ultima, $actual + 2);

        if ($fin - $inicio < 4) {
            $inicio = max(1, $fin - 4);
            $fin = min($ultima, $inicio + 4);
        }
    @endphp

    <nav class="paginacion-asistencias" aria-label="Paginación de asistencias">

        {{-- ANTERIOR --}}
        @if ($asistencias->onFirstPage())
            <span class="deshabilitada">‹</span>
        @else
            <a href="{{ $asistencias->previousPageUrl() }}"
               aria-label="Página anterior">
                ‹
            </a>
        @endif

        {{-- PRIMERA PÁGINA --}}
        @if ($inicio > 1)
            <a href="{{ $asistencias->url(1) }}">
                1
            </a>

            @if ($inicio > 2)
                <span class="puntos">...</span>
            @endif
        @endif

        {{-- NÚMEROS DE PÁGINA --}}
        @for ($pagina = $inicio; $pagina <= $fin; $pagina++)

            @if ($pagina == $actual)
                <span class="activa" aria-current="page">
                    {{ $pagina }}
                </span>
            @else
                <a href="{{ $asistencias->url($pagina) }}">
                    {{ $pagina }}
                </a>
            @endif

        @endfor

        {{-- ÚLTIMA PÁGINA --}}
        @if ($fin < $ultima)

            @if ($fin < $ultima - 1)
                <span class="puntos">...</span>
            @endif

            <a href="{{ $asistencias->url($ultima) }}">
                {{ $ultima }}
            </a>
        @endif

        {{-- SIGUIENTE --}}
        @if ($asistencias->hasMorePages())
            <a href="{{ $asistencias->nextPageUrl() }}"
               aria-label="Página siguiente">
                ›
            </a>
        @else
            <span class="deshabilitada">›</span>
        @endif

    </nav>

@endif

@endsection
