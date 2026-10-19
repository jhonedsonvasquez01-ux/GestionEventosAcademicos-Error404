<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#123a72">
    <title>@yield('titulo', 'Gestión de Eventos Académicos') | GEA</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="app-shell">
        <aside class="sidebar">
            <a class="brand" href="{{ route('dashboard') }}">
                <span class="brand-mark">GEA</span>
                <span class="brand-copy"><strong>Gestión Académica</strong><small>Panel de administración</small></span>
            </a>
            <div class="nav-label">MENÚ PRINCIPAL</div>
            <nav class="main-nav" aria-label="Navegación principal">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><span class="nav-icon">⌂</span> Inicio</a>
                <a class="nav-link {{ request()->routeIs('eventos.*') ? 'active' : '' }}" href="{{ route('eventos.index') }}"><span class="nav-icon">▣</span> Eventos</a>
                <a class="nav-link {{ request()->routeIs('actividades.*') ? 'active' : '' }}" href="{{ route('actividades.index') }}"><span class="nav-icon">◷</span> Actividades</a>
                <a class="nav-link {{ request()->routeIs('participantes.*') ? 'active' : '' }}" href="{{ route('participantes.index') }}"><span class="nav-icon">♙</span> Participantes</a>
                <a class="nav-link {{ request()->routeIs('expositores.*') ? 'active' : '' }}" href="{{ route('expositores.index') }}"><span class="nav-icon">▤</span> Expositores</a>
                <a class="nav-link {{ request()->routeIs('inscripciones.*') ? 'active' : '' }}" href="{{ route('inscripciones.index') }}"><span class="nav-icon">☷</span> Inscripciones</a>
                <a class="nav-link {{ request()->routeIs('asistencias.*') ? 'active' : '' }}" href="{{ route('asistencias.index') }}"><span class="nav-icon">✓</span> Asistencias</a>
                <a class="nav-link {{ request()->routeIs('certificados.*') ? 'active' : '' }}" href="{{ route('certificados.index') }}"><span class="nav-icon">▧</span> Certificados</a>
            </nav>
            <div class="sidebar-bottom"><span class="status-dot"></span> Sistema operativo<small>Gestión de Eventos Académicos</small></div>
        </aside>

        <div class="main-area">
            <header class="topbar">
                <div><span class="eyebrow">SISTEMA DE ADMINISTRACIÓN</span><div class="topbar-title">Gestión de Eventos Académicos</div></div>
                <div class="topbar-chip"><span class="user-avatar">A</span><span>Administrador</span></div>
            </header>
            <main class="page-content">
                <div class="page-heading"><div><p class="breadcrumb">GEA <span>/</span> @yield('titulo', 'Inicio')</p><h1>@yield('titulo', 'Panel principal')</h1></div></div>
                @if(session('success'))<div class="alert alert-success"><span class="alert-symbol">✓</span><div>{{ session('success') }}</div></div>@endif
                @if($errors->any())<div class="alert alert-error"><span class="alert-symbol">!</span><div><strong>Revisa los siguientes datos:</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div></div>@endif
                <section class="content-card">@yield('contenido')</section>
                <footer class="page-footer"><span>© {{ date('Y') }} GEA · Gestión de Eventos Académicos</span><span>Interfaz sencilla · Sin frameworks CSS</span></footer>
            </main>
        </div>
    </div>
</body>
</html>
