
@extends('layouts.app')

@section('titulo', 'Inscripciones')

@section('contenido')

<style>
    .paginacion-inscripciones {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        gap: 4px;
        margin: 25px 0;
    }

    .paginacion-inscripciones a,
    .paginacion-inscripciones span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 38px;
        height: 38px;
        padding: 0 10px;
        border-radius: 5px;
        background: #14253e;
        color: #ffffff;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        box-sizing: border-box;
    }

    .paginacion-inscripciones a:hover {
        background: #2563a6;
        color: #ffffff;
    }

    .paginacion-inscripciones .pagina-activa {
        background: #2563a6;
        color: #ffffff;
        font-weight: 700;
    }

    .paginacion-inscripciones .pagina-deshabilitada {
        background: #dce2e9;
        color: #8490a0;
        cursor: not-allowed;
    }

    .paginacion-inscripciones .puntos {
        background: transparent;
        color: #475569;
    }
</style>

<h2>Inscripciones</h2>

<p>
    <a href="{{ route('inscripciones.create') }}">
        Registrar inscripción
    </a>
</p>

<table>
    <tr>
        <th>Participante</th>
        <th>Evento</th>
        <th>Fecha</th>
        <th>Estado</th>
        <th>Actividades</th>
        <th>Acciones</th>
    </tr>

    @forelse($inscripciones as $i)
        <tr>
            <td>
                {{ $i->participante->nombre }}
                {{ $i->participante->apellido }}
            </td>

            <td>{{ $i->evento->nombre }}</td>

            <td>
                {{ $i->fecha_inscripcion->format('d/m/Y') }}
            </td>

            <td>{{ $i->estado }}</td>

            <td>
                {{ $i->actividades->pluck('nombre')->join(', ') }}
            </td>

            <td>
                <a href="{{ route('inscripciones.show', $i) }}">
                    Ver
                </a>

                <a href="{{ route('inscripciones.edit', $i) }}">
                    Editar
                </a>

                <form
                    class="inline"
                    method="POST"
                    action="{{ route('inscripciones.destroy', $i) }}"
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
            <td colspan="6">
                No hay inscripciones.
            </td>
        </tr>
    @endforelse
</table>

@if ($inscripciones->hasPages())
    <nav class="paginacion-inscripciones" aria-label="Paginación de inscripciones">

        {{-- Botón anterior --}}
        @if ($inscripciones->onFirstPage())
            <span class="pagina-deshabilitada">‹</span>
        @else
            <a href="{{ $inscripciones->previousPageUrl() }}"
               aria-label="Página anterior">
                ‹
            </a>
        @endif

        {{-- Números de página --}}
        @php
            $actual = $inscripciones->currentPage();
            $ultima = $inscripciones->lastPage();

            $inicio = max(1, $actual - 3);
            $fin = min($ultima, $actual + 3);

            if ($fin - $inicio < 6) {
                $inicio = max(1, $fin - 6);
                $fin = min($ultima, $inicio + 6);
            }
        @endphp

        @if ($inicio > 1)
            <a href="{{ $inscripciones->url(1) }}">1</a>

            @if ($inicio > 2)
                <span class="puntos">...</span>
            @endif
        @endif

        @for ($pagina = $inicio; $pagina <= $fin; $pagina++)
            @if ($pagina == $actual)
                <span class="pagina-activa" aria-current="page">
                    {{ $pagina }}
                </span>
            @else
                <a href="{{ $inscripciones->url($pagina) }}">
                    {{ $pagina }}
                </a>
            @endif
        @endfor

        @if ($fin < $ultima)
            @if ($fin < $ultima - 1)
                <span class="puntos">...</span>
            @endif

            <a href="{{ $inscripciones->url($ultima) }}">
                {{ $ultima }}
            </a>
        @endif

        {{-- Botón siguiente --}}
        @if ($inscripciones->hasMorePages())
            <a href="{{ $inscripciones->nextPageUrl() }}"
               aria-label="Página siguiente">
                ›
            </a>
        @else
            <span class="pagina-deshabilitada">›</span>
        @endif

    </nav>
@endif

@endsection
