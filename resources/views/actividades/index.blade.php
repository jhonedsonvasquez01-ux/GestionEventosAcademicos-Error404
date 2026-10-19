
@extends('layouts.app')

@section('titulo', 'Actividades')

@section('contenido')

<style>
    /* PAGINACIÓN PERSONALIZADA DE ACTIVIDADES */

    .paginacion-actividades {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        gap: 5px;
        margin: 25px 0;
    }

    .paginacion-actividades a,
    .paginacion-actividades span {
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

    .paginacion-actividades a:hover {
        background: #2563a6;
        color: #ffffff;
    }

    .paginacion-actividades .activa {
        background: #2563a6;
        color: #ffffff;
    }

    .paginacion-actividades .deshabilitada {
        background: #dce2e9;
        color: #8490a0;
        cursor: not-allowed;
    }

    .paginacion-actividades .puntos {
        background: transparent;
        color: #475569;
    }
</style>

<h2>Gestión de actividades</h2>

@if (session('success'))
    <p>{{ session('success') }}</p>
@endif

<p>
    <a href="{{ route('actividades.create') }}">
        Registrar nueva actividad
    </a>
</p>

<table>
    <thead>
        <tr>
            <th>Actividad</th>
            <th>Evento</th>
            <th>Tipo</th>
            <th>Fecha</th>
            <th>Horario</th>
            <th>Sala</th>
            <th>Cupo</th>
            <th>Acciones</th>
        </tr>
    </thead>

    <tbody>
        @forelse ($actividades as $actividad)
            <tr>
                <td>{{ $actividad->nombre }}</td>

                <td>{{ $actividad->evento->nombre }}</td>

                <td>{{ $actividad->tipo }}</td>

                <td>
                    {{ $actividad->fecha->format('d/m/Y') }}
                </td>

                <td>
                    {{ substr($actividad->hora_inicio, 0, 5) }}
                    -
                    {{ substr($actividad->hora_fin, 0, 5) }}
                </td>

                <td>{{ $actividad->sala }}</td>

                <td>{{ $actividad->cupo }}</td>

                <td>
                    <a href="{{ route('actividades.show', $actividad) }}">
                        Ver
                    </a>

                    <a href="{{ route('actividades.edit', $actividad) }}">
                        Editar
                    </a>

                    <form
                        class="inline"
                        action="{{ route('actividades.destroy', $actividad) }}"
                        method="POST"
                        onsubmit="return confirm('¿Eliminar esta actividad?')"
                    >
                        @csrf
                        @method('DELETE')

                        <button type="submit">
                            Eliminar
                        </button>
                    </form>
                </td>
            </tr>

        @empty
            <tr>
                <td colspan="8">
                    No hay actividades registradas.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

{{-- PAGINACIÓN PERSONALIZADA --}}

@if ($actividades->hasPages())

    @php
        $actual = $actividades->currentPage();
        $ultima = $actividades->lastPage();

        $inicio = max(1, $actual - 2);
        $fin = min($ultima, $actual + 2);

        if ($fin - $inicio < 4) {
            $inicio = max(1, $fin - 4);
            $fin = min($ultima, $inicio + 4);
        }
    @endphp

    <nav class="paginacion-actividades"
         aria-label="Paginación de actividades">

        {{-- ANTERIOR --}}
        @if ($actividades->onFirstPage())
            <span class="deshabilitada">‹</span>
        @else
            <a href="{{ $actividades->previousPageUrl() }}"
               aria-label="Página anterior">
                ‹
            </a>
        @endif

        {{-- PRIMERA PÁGINA --}}
        @if ($inicio > 1)
            <a href="{{ $actividades->url(1) }}">
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
                <a href="{{ $actividades->url($pagina) }}">
                    {{ $pagina }}
                </a>
            @endif

        @endfor

        {{-- ÚLTIMA PÁGINA --}}
        @if ($fin < $ultima)

            @if ($fin < $ultima - 1)
                <span class="puntos">...</span>
            @endif

            <a href="{{ $actividades->url($ultima) }}">
                {{ $ultima }}
            </a>
        @endif

        {{-- SIGUIENTE --}}
        @if ($actividades->hasMorePages())
            <a href="{{ $actividades->nextPageUrl() }}"
               aria-label="Página siguiente">
                ›
            </a>
        @else
            <span class="deshabilitada">›</span>
        @endif

    </nav>

@endif

@endsection
