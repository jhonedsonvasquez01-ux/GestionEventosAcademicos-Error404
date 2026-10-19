
@extends('layouts.app')

@section('titulo', 'Participantes')

@section('contenido')

<style>
    /* PAGINACIÓN PERSONALIZADA DE PARTICIPANTES */

    .paginacion-participantes {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        gap: 5px;
        margin: 25px 0;
    }

    .paginacion-participantes a,
    .paginacion-participantes span {
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

    .paginacion-participantes a:hover {
        background: #2563a6;
        color: #ffffff;
    }

    .paginacion-participantes .activa {
        background: #2563a6;
        color: #ffffff;
    }

    .paginacion-participantes .deshabilitada {
        background: #dce2e9;
        color: #8490a0;
        cursor: not-allowed;
    }

    .paginacion-participantes .puntos {
        background: transparent;
        color: #475569;
    }
</style>

<h2>Participantes</h2>

<p>
    <a href="{{ route('participantes.create') }}">
        Registrar
    </a>
</p>

<table>
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>CI</th>
            <th>Correo</th>
            <th>Teléfono</th>
            <th>Acciones</th>
        </tr>
    </thead>

    <tbody>
        @forelse($participantes as $participante)
            <tr>
                <td>{{ $participante->nombre }}</td>

                <td>{{ $participante->apellido }}</td>

                <td>{{ $participante->ci }}</td>

                <td>{{ $participante->email }}</td>

                <td>{{ $participante->telefono }}</td>

                <td>
                    <a href="{{ route('participantes.show', $participante) }}">
                        Ver
                    </a>

                    <a href="{{ route('participantes.edit', $participante) }}">
                        Editar
                    </a>

                    <form
                        class="inline"
                        method="POST"
                        action="{{ route('participantes.destroy', $participante) }}"
                        onsubmit="return confirm('¿Eliminar?')"
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
                    No hay registros.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

{{-- PAGINACIÓN PERSONALIZADA --}}

@if ($participantes->hasPages())

    @php
        $actual = $participantes->currentPage();
        $ultima = $participantes->lastPage();

        $inicio = max(1, $actual - 2);
        $fin = min($ultima, $actual + 2);

        if ($fin - $inicio < 4) {
            $inicio = max(1, $fin - 4);
            $fin = min($ultima, $inicio + 4);
        }
    @endphp

    <nav class="paginacion-participantes"
         aria-label="Paginación de participantes">

        {{-- ANTERIOR --}}
        @if ($participantes->onFirstPage())
            <span class="deshabilitada">‹</span>
        @else
            <a href="{{ $participantes->previousPageUrl() }}"
               aria-label="Página anterior">
                ‹
            </a>
        @endif

        {{-- PRIMERA PÁGINA --}}
        @if ($inicio > 1)
            <a href="{{ $participantes->url(1) }}">
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
                <a href="{{ $participantes->url($pagina) }}">
                    {{ $pagina }}
                </a>
            @endif

        @endfor

        {{-- ÚLTIMA PÁGINA --}}
        @if ($fin < $ultima)

            @if ($fin < $ultima - 1)
                <span class="puntos">...</span>
            @endif

            <a href="{{ $participantes->url($ultima) }}">
                {{ $ultima }}
            </a>
        @endif

        {{-- SIGUIENTE --}}
        @if ($participantes->hasMorePages())
            <a href="{{ $participantes->nextPageUrl() }}"
               aria-label="Página siguiente">
                ›
            </a>
        @else
            <span class="deshabilitada">›</span>
        @endif

    </nav>

@endif

@endsection
