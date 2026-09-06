@php
    $userSettings = null;
    if (auth()->check() && \Illuminate\Support\Facades\Schema::hasTable('user_settings')) {
        $userSettings = \App\Models\UserSetting::where('user_id', auth()->id())->first();
    }
@endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" id="appHtml" class="{{ ($userSettings->theme ?? 'light') === 'dark' ? 'dark-mode' : '' }}">
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
        @auth
        @php
            $fontFamily = $userSettings->font_family ?? 'Inter';
            $fontSize = $userSettings->font_size ?? 'medium';

            $fontFamilyMap = [
                'Inter' => "'Inter', 'Nunito', system-ui, -apple-system, sans-serif",
                'Nunito' => "'Nunito', system-ui, -apple-system, sans-serif",
                'system-ui' => "system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif",
                'Arial' => "Arial, Helvetica, sans-serif",
            ];

            $fontSizeMap = [
                'small' => '14px',
                'medium' => '16px',
                'large' => '18px',
            ];

            $appliedFont = $fontFamilyMap[$fontFamily] ?? $fontFamilyMap['Inter'];
            $appliedSize = $fontSizeMap[$fontSize] ?? $fontSizeMap['medium'];
        @endphp
        <style>
            :root {
                --user-font-family: {{ $appliedFont }};
                --user-font-size: {{ $appliedSize }};
            }
            body {
                font-family: var(--user-font-family) !important;
                font-size: var(--user-font-size) !important;
            }
        </style>
        @endauth
        <!-- Scripts -->
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <!-- Top Navbar -->
        <nav class="navbar navbar-light bg-white shadow-sm top-navbar">
            <div class="container-fluid flex-nowrap">
                <button class="btn btn-outline-secondary sidebar-toggle-btn me-2 flex-shrink-0" id="sidebarToggle" type="button" title="Toggle Sidebar">
                    <i class="bi bi-list"></i>
                </button>
                <a class="navbar-brand me-auto" href="{{ route('home') }}" title="Sistem eDP JPN Melaka">
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

        <div class="d-flex wrapper">
            <!-- Left Sidebar -->
            <nav class="sidebar bg-dark text-white" id="sidebar">
                <div class="sidebar-header p-3">
                    <h5 class="mb-0 sidebar-title">Menu</h5>
                </div>
                <ul class="list-unstyled components">
                    <li class="{{ request()->is('home') ? 'active' : '' }}">
                        <a href="{{ route('home') }}" class="sidebar-link">
                            <i class="bi bi-speedometer2"></i>
                            <span class="link-text">Dashboard</span>
                        </a>
                    </li>

                    @auth
                    {{-- Pengurusan Pengguna (Collapsible) --}}
                    @if(auth()->user()->canany(['create-role', 'edit-role', 'delete-role', 'create-user', 'edit-user', 'delete-user']))
                    <li class="sidebar-dropdown">
                        <a href="#userMgmtSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle sidebar-link">
                            <i class="bi bi-people-fill"></i>
                            <span class="link-text">Pengurusan Pengguna</span>
                        </a>
                        <ul class="collapse list-unstyled {{ request()->is('roles*') || request()->is('users*') ? 'show' : '' }}" id="userMgmtSubmenu">
                            @canany(['create-role', 'edit-role', 'delete-role'])
                            <li>
                                <a href="{{ route('roles.index') }}" class="sidebar-link ps-4">
                                    <i class="bi bi-shield-lock"></i>
                                    <span class="link-text">Peranan (Roles)</span>
                                </a>
                            </li>
                            @endcanany
                            @canany(['create-user', 'edit-user', 'delete-user'])
                            <li>
                                <a href="{{ route('users.index') }}" class="sidebar-link ps-4">
                                    <i class="bi bi-person-badge"></i>
                                    <span class="link-text">Pengguna (Users)</span>
                                </a>
                            </li>
                            @endcanany
                        </ul>
                    </li>
                    @endif

                    {{-- Dialog Prestasi (Collapsible) --}}
                    @canany(['view-dialog-prestasi', 'create-dialog-prestasi', 'edit-dialog-prestasi', 'delete-dialog-prestasi', 'feedback-dialog-prestasi'])
                    <li class="sidebar-dropdown">
                        <a href="#dialogPrestasiSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle sidebar-link">
                            <i class="bi bi-file-earmark-text"></i>
                            <span class="link-text">Dialog Prestasi</span>
                        </a>
                        <ul class="collapse list-unstyled {{ request()->is('dialog-prestasi*') ? 'show' : '' }}" id="dialogPrestasiSubmenu">
                            @can('view-dialog-prestasi')
                            <li>
                                <a href="{{ route('dialog-prestasi.index') }}" class="sidebar-link ps-4">
                                    <i class="bi bi-list-ul"></i>
                                    <span class="link-text">Senarai Laporan</span>
                                </a>
                            </li>
                            @endcan
                             @can('create-dialog-prestasi')
                             <li>
                                 <a href="{{ route('dialog-prestasi.create') }}" class="sidebar-link ps-4">
                                     <i class="bi bi-plus-circle"></i>
                                     <span class="link-text">Tambah Laporan</span>
                                 </a>
                             </li>
                             @endcan
                             @can('feedback-dialog-prestasi')
                             <li>
                                 <a href="{{ route('dialog-prestasi.index') }}#maklum-balas" class="sidebar-link ps-4">
                                     <i class="bi bi-chat-dots"></i>
                                     <span class="link-text">Maklum Balas</span>
                                 </a>
                             </li>
                             @endcan
                         </ul>
                     </li>
                     @endcanany
                    @endauth

                    {{-- Sektor & Unit (Collapsible) --}}
                    @can('manage-sektor-unit')
                    <li class="sidebar-dropdown">
                        <a href="#strukturSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle sidebar-link">
                            <i class="bi bi-diagram-3"></i>
                            <span class="link-text">Sektor & Unit</span>
                        </a>
                        <ul class="collapse list-unstyled {{ request()->is('sektor*') || request()->is('unit*') ? 'show' : '' }}" id="strukturSubmenu">
                            <li>
                                <a href="{{ route('sektor.index') }}" class="sidebar-link ps-4">
                                    <i class="bi bi-diagram-2"></i>
                                    <span class="link-text">Sektor</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('unit.index') }}" class="sidebar-link ps-4">
                                    <i class="bi bi-stack"></i>
                                    <span class="link-text">Unit</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endcan

                    {{-- Tetapan --}}
                    @can('manage-settings')
                    <li class="{{ request()->is('settings*') ? 'active' : '' }}">
                        <a href="{{ route('settings.index') }}" class="sidebar-link">
                            <i class="bi bi-gear"></i>
                            <span class="link-text">Tetapan</span>
                        </a>
                    </li>
                    @endcan
                </ul>

            </nav>

            <!-- Main Content -->
            <div id="content" class="flex-grow-1 d-flex flex-column">
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
        </div>
    </div>
    @stack('scripts')
</body>
</html>
