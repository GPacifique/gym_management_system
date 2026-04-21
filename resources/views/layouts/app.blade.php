<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Gym Management') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Custom Sidebar Fix -->
    <link rel="stylesheet" href="{{ asset('css/sidebar-fix.css') }}">

    <!-- App Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')

</head>

<body>
<body class="bg-light">

   
    <div class="top-info    d-fixed justify-content-end align-items-center p-2 border-top bg-white shadow-sm"> 
         <x-top-info />  
         </div>
    <!-- Sidebar -->
    <div class="d-flex h-screen overflow-hidden">
    <x-sidebar :active="request()->route()->getName()" />   
    <div x-data="{ open: false }" class="flex h-screen overflow-hidden">
    <x-sidebar :active="request()->route()->getName()" />      

    </div> 
    </div>
    <!-- Main Content -->
    <div class="main-content">

        <!-- Header -->
        <header class="d-flex justify-content-between align-items-center mb-4">

            <!-- Mobile Toggle -->
            <button id="sidebarToggle" class="btn btn-light border d-md-none">
                <i class="bi bi-list fs-5"></i>
            </button>

            <!-- Dynamic Header -->
            <div class="flex-grow-1 ms-2">
                {{ $header ?? '' }}
                @yield('header')
            </div>

        </header>

        <!-- Page Content -->
        <main class="content">

            @hasSection('content')
                @yield('content')
            @else
                {{ $slot ?? '' }}
            @endif

        </main>

    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/sidebar-fix.js') }}"></script>

    @stack('scripts')

</body>
</html>