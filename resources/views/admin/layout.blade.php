<!doctype html>
<html lang="{{ app()->getLocale() }}" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title') - {{ __('ui.panel') }}</title>

    <link rel="icon" href="{{ asset('img/logo.png') }}">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>


    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css"
        rel="stylesheet">

    <!-- Admin UI -->
    <link href="{{ asset('css/admin-ui.css') }}" rel="stylesheet">

    @stack('styles')
</head>

@php
    $user = auth()->user();
    $role = $user?->role;
@endphp

<body>

    <!-- ================= NAVBAR ================= -->
    <nav class="navbar navbar-expand-lg navbar-admin shadow-sm">
        <div class="container-fluid">

            <!-- Brand -->
            <a href="{{ route('admin.dashboard') }}"
                class="d-flex align-items-center gap-2 text-decoration-none navbar-brand-wrap">

                <img src="{{ asset('img/logo.png') }}" class="rounded-circle brand-logo" alt="CaMenu QR">

                <div class="brand-text">
                    <div class="small text-muted d-none d-md-block">
                        {{ __('ui.admin') }}
                    </div>
                    <div class="fw-bold brand-title">
                        {{ __('ui.panel') }}
                    </div>
                </div>
            </a>

            <!-- Right -->
            <ul class="navbar-nav ms-auto align-items-center gap-1">

                <!-- 🌐 Language Switch -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle nav-icon" href="#" data-bs-toggle="dropdown">
                        🌐 <span class="d-none d-md-inline">
                            {{ app()->getLocale() === 'km' ? 'ខ្មែរ' : 'EN' }}
                        </span>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li>
                            <a class="dropdown-item" href="{{ route('lang.switch', 'km') }}">
                                🇰🇭 ខ្មែរ
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">
                                🇬🇧 English
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- User dropdown -->
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle user-dropdown d-flex align-items-center gap-2" href="#"
                            data-bs-toggle="dropdown">

                            <img src="{{ $user->avatar
                                ? asset('storage/avatars/' . $user->avatar)
                                : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}"
                                class="rounded-circle user-avatar">

                            <span class="d-none d-md-inline user-name">
                                {{ $user->name }}
                            </span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.profile.show') }}">
                                    <i class="bi bi-person me-2"></i>
                                    {{ __('ui.my_profile') }}
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.profile.edit') }}">
                                    <i class="bi bi-pencil me-2"></i>
                                    {{ __('ui.edit_profile') }}
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('admin.logout') }}">
                                    @csrf
                                    <button class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>
                                        {{ __('ui.logout') }}
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endauth

            </ul>
        </div>
    </nav>


    <!-- ================= LAYOUT ================= -->
    <div class="d-flex">

        <!-- ========== SIDEBAR ========== -->
        <aside class="sidebar">
            <ul class="nav flex-column">

                <li>
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                        href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-house"></i> {{ __('ui.dashboard') }}
                    </a>
                </li>

                <li>
                    <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"
                        href="{{ route('admin.categories.index') }}">
                        <i class="bi bi-tags"></i> {{ __('ui.categories') }}
                    </a>
                </li>

                <li>
                    <a class="nav-link {{ request()->routeIs('admin.restaurants.*') ? 'active' : '' }}"
                        href="{{ route('admin.restaurants.index') }}">
                        <i class="bi bi-shop"></i> {{ __('ui.shops') }}
                    </a>
                </li>

                <li>
                    <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                        href="{{ route('admin.users.index') }}">
                        <i class="bi bi-people"></i> {{ __('ui.users') }}
                    </a>
                </li>

                @if ($role?->name === 'admin')
                    <li>
                        <a class="nav-link {{ request()->routeIs('admin.restaurant-subscriptions.*') ? 'active' : '' }}"
                            href="{{ route('admin.restaurant-subscriptions.index') }}">
                            <i class="bi bi-card-checklist"></i> {{ __('ui.subscriptions') }}
                        </a>
                    </li>

                    <li>
                        <a class="nav-link {{ request()->routeIs('admin.subscription-plans.*') ? 'active' : '' }}"
                            href="{{ route('admin.subscription-plans.index') }}">
                            <i class="bi bi-stickies"></i> {{ __('ui.plans') }}
                        </a>
                    </li>
                @endif

            </ul>

            <div class="sidebar-footer">
                {{ __('ui.version') }} 1.0
            </div>
        </aside>

        <!-- ========== CONTENT ========== -->
        <main class="content-area flex-fill">
            <div class="container-fluid">

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- ================= SCRIPTS ================= -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function toggleTheme() {
            const html = document.documentElement;
            const next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', next);
            localStorage.setItem('theme', next);
        }

        (() => {
            const saved = localStorage.getItem('theme');
            if (saved) document.documentElement.setAttribute('data-theme', saved);
        })();
    </script>

    @stack('scripts')

</body>

</html>
