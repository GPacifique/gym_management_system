<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'GymMate') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
        <link rel="alternate icon" href="{{ asset('images/favicon.svg') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- App Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* small helpers for auth page when needed */
        .btn-loading { pointer-events: none; opacity: 0.8; }
    </style>
    </head>
    <body class="font-sans text-gray-100 antialiased bg-gradient-to-tr from-slate-900 via-slate-800 to-slate-900 min-h-screen">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div>
                <a href="/" class="flex items-center no-underline">
                    <x-application-logo class="w-16 h-16 fill-current text-white mr-3" />
                    <span class="text-white font-bold text-lg">{{ config('app.name', 'GymMate') }}</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-6 bg-white/5 backdrop-blur-sm rounded-lg shadow-lg border border-white/5">
                @if (isset($slot))
                    {{ $slot }}
                @else
                    @yield('content')
                @endif
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Password visibility toggle
                const pwd = document.getElementById('password');
                if (pwd) {
                    const parent = pwd.parentElement;
                    parent.style.position = 'relative';

                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'absolute right-3 top-1/2 -translate-y-1/2 text-sm text-gray-400';
                    btn.setAttribute('aria-label', 'Toggle password visibility');
                    btn.innerText = 'Show';
                    btn.addEventListener('click', () => {
                        if (pwd.type === 'password') {
                            pwd.type = 'text';
                            btn.innerText = 'Hide';
                        } else {
                            pwd.type = 'password';
                            btn.innerText = 'Show';
                        }
                    });
                    parent.appendChild(btn);
                }

                // Form submit: show loading state on submit button
                const authForm = document.querySelector('form[method="POST"][action$="/login"]') || document.querySelector('form[method="POST"]');
                if (authForm) {
                    authForm.addEventListener('submit', function (e) {
                        const submit = authForm.querySelector('button[type="submit"]');
                        if (submit) {
                            submit.classList.add('btn-loading');
                            const originalText = submit.innerText;
                            submit.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="animate-spin inline-block mr-2" style="height:1rem;width:1rem;vertical-align:middle;" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" stroke-opacity="0.25"></circle><path d="M4 12a8 8 0 018-8" stroke="currentColor" stroke-width="4" stroke-linecap="round"></path></svg>' + originalText;
                        }
                    });
                }
            });
        </script>
    </body>
</html>
