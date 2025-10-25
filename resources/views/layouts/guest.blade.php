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

    <!-- Styles: Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- App Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            /* Auth page specific styles */
            body.auth-bg {
                background: linear-gradient(135deg,#0f172a 0%,#111827 60%);
                min-height:100vh;
            }

            .auth-card {
                background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.03));
                border-radius: 0.75rem;
                box-shadow: 0 10px 30px rgba(2,6,23,0.6);
                border: 1px solid rgba(255,255,255,0.04);
            }

            .brand-title {
                color: #fff;
                font-weight: 700;
                font-size: 1.5rem;
                letter-spacing: 1px;
            }

            .password-toggle {
                position: absolute;
                right: 0.75rem;
                top: 50%;
                transform: translateY(-50%);
                background: transparent;
                border: none;
                color: #6b7280;
                cursor: pointer;
            }

            .btn-loading {
                pointer-events: none;
                opacity: 0.8;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased auth-bg">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div>
                <a href="/" class="d-flex align-items-center text-decoration-none">
                    <x-application-logo class="w-16 h-16 fill-current text-white me-3" />
                    <span class="brand-title">{{ config('app.name', 'GymMate') }}</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-6 auth-card overflow-hidden sm:rounded-lg">
                @if (isset($slot))
                    {{ $slot }}
                @else
                    @yield('content')
                @endif
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Password visibility toggle: inject a toggle button next to password input
                const pwd = document.getElementById('password');
                if (pwd) {
                    const parent = pwd.parentElement;
                    parent.style.position = 'relative';

                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'password-toggle';
                    btn.setAttribute('aria-label', 'Toggle password visibility');
                    btn.innerHTML = 'Show';
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
                            // simple spinner
                            submit.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="animate-spin me-2" style="height:1rem;width:1rem;vertical-align:middle;" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" stroke-opacity="0.25"></circle><path d="M4 12a8 8 0 018-8" stroke="currentColor" stroke-width="4" stroke-linecap="round"></path></svg>' + 'Logging in...';
                        }
                    });
                }
            });
        </script>
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
