
@extends('layouts.app')

@section('titulo', 'Certificados')

@section('contenido')

<style>
    .paginacion-certificados {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        gap: 5px;
        margin: 25px 0;
    }

    .paginacion-certificados a,
    .paginacion-certificados span {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        min-width: 36px;
        height: 36px;
        padding: 0 10px;
        border-radius: 5px;
        background: #14253e;
        color: white;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        box-sizing: border-box;
    }

    .paginacion-certificados a:hover {
        background: #2563a6;
        color: white;
    }

    .paginacion-certificados .activa {
        background: #2563a6;
        color: white;
    }

    .paginacion-certificados .deshabilitada {
        background: #dce2e9;
        color: #8490a0;
    }

    .paginacion-certificados .puntos {
        background: transparent;
        color: #475569;
    }
</style>

<h2>Certificados</h2>

<p>
    Se puede emitir un certificado cuando el participante
    tiene al menos 80% de asistencia en las actividades
    seleccionadas en su inscripción.
</p>

<h3>Inscripciones elegibles</h3>

@if($elegibles->isEmpty())
    <p>No hay inscripciones que cumplan el requisito por ahora.</p>
@else
    <table>
        <tr>
            <th>Participante</th>
            <th>Evento</th>
            <th>Asistencia</th>
            <th>Acción</th>
        </tr>

        @foreach($elegibles as $i)
            @php
                $total = $i->actividades->count();

                $presentes = $i->asistencias
                    ->where('asistio', true)
                    ->whereIn(
                        'actividad_id',
                        $i->actividades->pluck('id')
                    )
                    ->count();
            @endphp

            <tr>
                <td>
                    {{ $i->participante->nombre }}
                    {{ $i->participante->apellido }}
                </td>

                <td>{{ $i->evento->nombre }}</td>

                <td>
                    {{ $presentes }}/{{ $total }}
                    ({{ $total > 0 ? round($presentes / $total * 100) : 0 }}%)
                </td>

                <td>
                    <form method="POST"
                          action="{{ route('certificados.store') }}">
                        @csrf

                        <input type="hidden"
                               name="inscripcion_id"
                               value="{{ $i->id }}">

                        <button type="submit">
                            Emitir certificado
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
@endif

<h3>Certificados emitidos</h3>

<table>
    <tr>
        <th>Código</th>
        <th>Participante</th>
        <th>Evento</th>
        <th>Fecha</th>
        <th>Acciones</th>
    </tr>

    @forelse($certificados as $c)
        <tr>
            <td>{{ $c->codigo }}</td>

            <td>
                {{ $c->inscripcion->participante->nombre }}
                {{ $c->inscripcion->participante->apellido }}
            </td>

            <td>{{ $c->inscripcion->evento->nombre }}</td>

            <td>
                {{ $c->fecha_emision->format('d/m/Y') }}
            </td>

            <td>
                <a href="{{ route('certificados.show', $c) }}">
                    Ver
                </a>

                <form class="inline"
                      method="POST"
                      action="{{ route('certificados.destroy', $c) }}">
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
            <td colspan="5">
                No hay certificados emitidos.
            </td>
        </tr>
    @endforelse
</table>

{{-- PAGINACIÓN PERSONALIZADA --}}

@if ($certificados->hasPages())

    @php
        $actual = $certificados->currentPage();
        $ultima = $certificados->lastPage();

        $inicio = max(1, $actual - 2);
        $fin = min($ultima, $actual + 2);

        if ($fin - $inicio < 4) {
            $inicio = max(1, $fin - 4);
            $fin = min($ultima, $inicio + 4);
        }
    @endphp

    <nav class="paginacion-certificados">

        {{-- ANTERIOR --}}
        @if ($certificados->onFirstPage())
            <span class="deshabilitada">‹</span>
        @else
            <a href="{{ $certificados->previousPageUrl() }}">
                ‹
            </a>
        @endif

        {{-- PRIMERA PÁGINA --}}
        @if ($inicio > 1)
            <a href="{{ $certificados->url(1) }}">1</a>

            @if ($inicio > 2)
                <span class="puntos">...</span>
            @endif
        @endif

        {{-- NÚMEROS DE PÁGINA --}}
        @for ($pagina = $inicio; $pagina <= $fin; $pagina++)

            @if ($pagina == $actual)
                <span class="activa">
                    {{ $pagina }}
                </span>
            @else
                <a href="{{ $certificados->url($pagina) }}">
                    {{ $pagina }}
                </a>
            @endif

        @endfor

        {{-- ÚLTIMA PÁGINA --}}
        @if ($fin < $ultima)

            @if ($fin < $ultima - 1)
                <span class="puntos">...</span>
            @endif

            <a href="{{ $certificados->url($ultima) }}">
                {{ $ultima }}
            </a>
        @endif

        {{-- SIGUIENTE --}}
        @if ($certificados->hasMorePages())
            <a href="{{ $certificados->nextPageUrl() }}">
                ›
            </a>
        @else
            <span class="deshabilitada">›</span>
        @endif

    </nav>

@endif

@endsection
