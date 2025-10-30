<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- CSRF --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Escuelita')</title>

    {{-- Favicon (Ícono de la pestaña) --}}
    {{-- Asegúrate de que esta ruta sea correcta para tu logo en public/images/ --}}
    <link rel="icon" type="image/png" href="{{ asset('images/escuelita-logo2.png') }}">

    {{-- Fonts --}}
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito:300,400,600,700,800" rel="stylesheet">

    {{-- Vite (Bootstrap + app.js) --}}
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        :root{
            /* Colores inspirados en tu logo */
            --brand: #103a68;      /* azul principal */
            --brand-2:#0d2f56;     /* azul oscuro */
            --brand-3:#1e4b82;     /* azul medio */
            --accent:#f1c40f;      /* dorado */
            --bg:#f7f9fc;          /* fondo suave */
            --text:#12263a;        /* texto principal */
        }

        html, body { height: 100%; }
        body{
            background: var(--bg);
            color: var(--text);
            font-family: 'Nunito', system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, 'Helvetica Neue', Arial, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', sans-serif;
        }

        /* NAVBAR */
        .navbar-escuelita{
            background: linear-gradient(90deg, var(--brand) 0%, var(--brand-3) 60%, var(--brand-2) 100%);
        }
        .navbar-escuelita .navbar-brand,
        .navbar-escuelita .nav-link,
        .navbar-escuelita .dropdown-toggle{
            color: #fff !important;
        }
        .navbar-escuelita .nav-link:hover,
        .navbar-escuelita .dropdown-item:hover{
            color: #fff !important;
            background-color: rgba(255,255,255,.08);
        }
        .navbar-escuelita .dropdown-menu{
            border: 0;
            border-radius: .75rem;
            overflow: hidden;
            box-shadow: 0 10px 24px rgba(0,0,0,.15);
        }
        .navbar-brand .brand-text{
            font-weight: 800;
            letter-spacing: .5px;
        }
        .brand-pill{
            background: rgba(241,196,15,.15);
            color: var(--accent);
            border: 1px solid rgba(241,196,15,.35);
            font-size: .7rem;
            padding: .15rem .4rem;
            border-radius: 999px;
            margin-left: .5rem;
        }

        /* BOTONES */
        .btn-primary{
            background-color: var(--brand-3);
            border-color: var(--brand-3);
        }
        .btn-primary:hover{
            background-color: var(--brand);
            border-color: var(--brand);
        }
        .btn-outline-primary{
            color: var(--brand-3);
            border-color: var(--brand-3);
        }
        .btn-outline-primary:hover{
            background-color: var(--brand-3);
            border-color: var(--brand-3);
            color: #fff;
        }
        .btn-accent{
            background-color: var(--accent);
            border-color: var(--accent);
            color: #122;
            font-weight: 700;
        }

        /* CARDS */
        .card{
            border: 0;
            border-radius: 1rem;
            box-shadow: 0 10px 24px rgba(0,0,0,.06);
        }
        .card-header{
            background: #fff;
            border-bottom: 1px solid #eef2f7;
            border-top-left-radius: 1rem !important;
            border-top-right-radius: 1rem !important;
        }

        /* FOOTER */
        .footer{
            background: var(--brand-2);
            color: rgba(255,255,255,.85);
        }
        .footer a{ color:#fff; text-decoration: none; }
        .footer a:hover{ text-decoration: underline; }
    </style>
</head>
<body>
    <div id="app" class="d-flex flex-column" style="min-height:100%;">
        {{-- NAV --}}
        <nav class="navbar navbar-expand-md navbar-escuelita sticky-top shadow-sm">
            <div class="container">
                
                {{-- Logo principal en la barra de navegación --}}
                <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                    <img src="{{ asset('images/escuelita-logo2.png') }}" alt="Logo de la Institución" 
                         width="40" height="40" class="rounded-1" style="height: 40px; width: 40px;">
                </a>

                <button class="navbar-toggler bg-light bg-opacity-10 border-0" type="button"
                        data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false"
                        aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    {{-- Left --}}
                    <ul class="navbar-nav me-auto">
                        @auth
                        <li class="nav-item"><a class="nav-link" href="{{ url('/home') }}">Inicio</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ url('/carreras') }}">Carreras</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ url('/alumnos') }}">Alumnos</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ url('/materias') }}">Materias</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ url('/cursos') }}">Cursos</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ url('/aulas') }}">Aulas</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ url('/edificios') }}">Edificios</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ url('/periodos') }}">Periodos</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ url('/inscripciones') }}">Inscripciones</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ url('/profesores') }}">Profesores</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ url('/areas') }}">Áreas</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('asignaciones.index') }}">Asignación de Clases</a></li>
                        <li class="nav-item">
                            
                            <a class="nav-link" href="{{ route('reportes.index') }}">
                                {{ __('Reportes') }}
                            </a>
                        </li>
                        @endauth
                    </ul>

                    {{-- Right --}}
                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">{{ __('Iniciar sesión') }}</a></li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">{{ __('Registrarme') }}</a></li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle px-3" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="{{ url('/home') }}">Panel</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Cerrar sesión') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        {{-- MAIN --}}
        <main class="flex-grow-1 py-4">
            <div class="container">
                {{-- Mensajes flash globales opcionales --}}
                @if ($message = Session::get('success'))
                    <div class="alert alert-success shadow-sm">{{ $message }}</div>
                @endif
                @yield('content')
            </div>
        </main>

        {{-- FOOTER --}}
        <footer class="footer mt-4 py-3">
            <div class="container d-flex flex-column flex-md-row align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <img src="{{ asset('images/escuelita-logo2.png') }}" width="22" height="22" alt="Escuelita">
                    <span>© {{ date('Y') }} Escuelita · Todos los derechos reservados</span>
                </div>
                <div class="mt-2 mt-md-0 small">
                    <a href="{{ url('/home') }}" class="me-3">Inicio</a>
                    <a href="mailto:soporte@example.com">Soporte</a>
                </div>
            </div>
        </footer>
    </div>
    @stack('scripts')
</body>
</html>