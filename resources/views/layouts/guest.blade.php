<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" id="appHtml">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Sistem eDP JPN Melaka</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app" class="d-flex flex-column min-vh-100">
        <!-- Top Navbar (tanpa sidebar toggle) -->
        <nav class="navbar navbar-light bg-white shadow-sm top-navbar">
            <div class="container-fluid flex-nowrap">
                <a class="navbar-brand me-auto" href="{{ url('/') }}" title="Sistem eDP JPN Melaka">
                    <i class="bi bi-building"></i>
                    <span class="navbar-brand-text">Sistem eDP JPN Melaka</span>
                </a>

                <div class="navbar-actions d-flex align-items-center flex-shrink-0">
                    {{-- Dark / Light Mode Toggle --}}
                    <button class="btn btn-sm theme-toggle-btn" id="themeToggle" type="button" title="Tukar Mod Gelap/Cerah">
                        <i class="bi bi-moon-stars-fill" id="themeIconDark"></i>
                        <i class="bi bi-sun-fill d-none" id="themeIconLight"></i>
                    </button>

                    @guest
                        @if (Route::has('login'))
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        @endif
                        @if (Route::has('register'))
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        @endif
                    @else
                        <div class="dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <i class="bi bi-person-circle me-1"></i>
                                <span class="navbar-user-name">{{ Auth::user()->name }}</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right"></i> {{ __('Logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    @endguest
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="py-4 flex-grow-1">
            <div class="container-fluid px-4">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success text-center" role="alert">
                        {{ $message }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        <footer class="app-footer bg-white border-top py-3 mt-auto">
            <div class="container-fluid px-4">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                        <small class="text-muted">
                            &copy; {{ date('Y') }} <strong>Jabatan Pendidikan Negeri Melaka</strong>. Hak Cipta Terpelihara.
                        </small>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <small class="text-muted">
                            <i class="bi bi-shield-check"></i> Sistem eDP — Versi 1.0
                        </small>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    @stack('scripts')
</body>
</html>
