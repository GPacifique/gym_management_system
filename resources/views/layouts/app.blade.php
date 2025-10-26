<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Gym Management') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('images/favicon.svg') }}">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Custom Sidebar Fix CSS (prevents content overlap) -->
    <link rel="stylesheet" href="{{ asset('css/sidebar-fix.css') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-light">
    {{-- Sidebar Component --}}
    <x-sidebar :active="request()->route()->getName()" />

    <div class="main-content">
        <header class="mb-4">
            <button id="sidebarToggle" class="btn btn-dark d-md-none mb-3">
                <i class="bi bi-list"></i>
            </button>
            {{-- Optional header slot from x-app-layout or header section --}}
            {{ $header ?? '' }}
            @yield('header')
        </header>

        <main class="content">
            {{-- Support both component slots and content section --}}
            @hasSection('content')
                @yield('content')
            @else
                {{ $slot ?? '' }}
            @endif
        </main>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Sidebar Fix JavaScript (handles dynamic content and mobile) -->
    <script src="{{ asset('js/sidebar-fix.js') }}"></script>
    
    @stack('scripts')
</body>
</html>
