@extends('layouts.app')
@section('titulo', 'Inicio')
@section('contenido')
    <div class="welcome-panel">
        <div class="welcome-copy">
            <span class="welcome-tag">BIENVENIDO AL SISTEMA</span>
            <h2>Organiza tus eventos académicos desde un solo lugar.</h2>
            <p>Administra eventos, actividades, participantes y el seguimiento de cada inscripción de manera sencilla.</p>
            <a class="primary-action" href="{{ route('eventos.create') }}">＋ Registrar un evento</a>
        </div>
        <div class="welcome-art" aria-hidden="true">
            <span class="art-square"></span>
            <span class="art-calendar">▦</span>
            <span class="art-dot"></span>
        </div>
    </div>

    <h3 class="section-heading">Módulos del sistema</h3>
    <div class="module-grid">
        <a class="module-card" href="{{ route('eventos.index') }}">
            <span class="module-icon">▣</span>
            <strong>Eventos</strong>
            <span>Crear y administrar eventos</span>
            <b>Explorar →</b>
        </a>
        <a class="module-card" href="{{ route('actividades.index') }}">
            <span class="module-icon">◷</span>
            <strong>Actividades</strong>
            <span>Organizar sesiones y horarios</span>
            <b>Explorar →</b>
        </a>
        <a class="module-card" href="{{ route('participantes.index') }}">
            <span class="module-icon">♙</span>
            <strong>Participantes</strong>
            <span>Gestionar datos de asistentes</span>
            <b>Explorar →</b>
        </a>
        <a class="module-card" href="{{ route('expositores.index') }}">
            <span class="module-icon">▤</span>
            <strong>Expositores</strong>
            <span>Registrar ponentes invitados</span>
            <b>Explorar →</b>
        </a>
        <a class="module-card" href="{{ route('inscripciones.index') }}">
            <span class="module-icon">☷</span>
            <strong>Inscripciones</strong>
            <span>Controlar registros a eventos</span>
            <b>Explorar →</b>
        </a>
        <a class="module-card" href="{{ route('asistencias.index') }}">
            <span class="module-icon">✓</span>
            <strong>Asistencias</strong>
            <span>Registrar participación</span>
            <b>Explorar →</b>
        </a>
        <a class="module-card" href="{{ route('certificados.index') }}">
            <span class="module-icon">▧</span>
            <strong>Certificados</strong>
            <span>Consultar y emitir certificados</span>
            <b>Explorar →</b>
        </a>
    </div>
@endsection