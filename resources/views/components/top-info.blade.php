<div class="w-100 bg-white border-bottom py-2 px-3 d-flex justify-content-between align-items-center small text-muted">

    <!-- LEFT SIDE -->
    <div class="d-flex align-items-center gap-3">

        <!-- APP NAME -->
        <span class="fw-semibold text-dark">
            {{ config('app.name', 'Gym Manager Pro') }}
        </span>

        <span class="d-none d-md-inline text-muted">
            • {{ now()->format('D, d M Y') }}
        </span>

        @if(\App\Support\GymContext::current())
            <span class="badge bg-primary bg-opacity-10 text-primary">
                <i class="bi bi-building me-1"></i>
                {{ \App\Support\GymContext::current()->name }}
            </span>
        @endif

    </div>

    <!-- RIGHT SIDE -->
    <div class="d-flex align-items-center gap-3">

        <a href="#" class="top-link">Docs</a>
        <a href="#" class="top-link">Support</a>
        <a href="#" class="top-link">Status</a>

    </div>
<nav x-data="{ open: false }"
     class="bg-white/80 backdrop-blur-md border-b border-gray-200 shadow-sm sticky top-0 z-50">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex justify-between h-16 items-center">

            <!-- LEFT -->


            <!-- RIGHT (DESKTOP USER) -->
            <div class="hidden sm:flex items-center space-x-4">

                <x-dropdown align="right" width="56">

                    <x-slot name="trigger">
                        <button class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-gray-100 transition">

                            <!-- Avatar -->
                            <div class="h-9 w-9 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-semibold">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>

                            <!-- User -->
                            <div class="text-left">
                                <div class="text-sm font-semibold text-gray-800">
                                    {{ Auth::user()->name }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ Auth::user()->email }}
                                </div>
                            </div>

                            <!-- Role -->
                            <span class="text-xs px-2 py-1 rounded-full bg-indigo-50 text-indigo-600 border border-indigo-100">
                                {{ ucfirst(Auth::user()->role) }}
                            </span>

                        </button>
                    </x-slot>

                    <x-slot name="content">

                        <div class="px-4 py-3 border-b">
                            <p class="text-sm font-medium">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                        </div>

                        <x-dropdown-link :href="route('profile.edit')">
                            👤 Profile
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                🚪 Log Out
                            </x-dropdown-link>
                        </form>

                    </x-slot>
                </x-dropdown>
            </div>

            <!-- MOBILE BUTTON -->
            <div class="sm:hidden flex items-center">

                <button @click="open = !open"
                        class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition">

                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">

                        <path x-show="!open"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M4 6h16M4 12h16M4 18h16"/>

                        <path x-show="open"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M6 18L18 6M6 6l12 12"/>

                    </svg>

                </button>
            </div>

        </div>
    </div>

    <!-- MOBILE MENU -->
    <div x-show="open"
         x-transition
         class="sm:hidden border-t bg-white">

        <div class="px-4 py-3 space-y-2">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                Dashboard
            </x-responsive-nav-link>
        </div>

        <div class="border-t px-4 py-3">
            <div class="text-sm font-semibold">{{ Auth::user()->name }}</div>
            <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>

            <div class="mt-3 space-y-2">

                <x-responsive-nav-link :href="route('profile.edit')">
                    Profile
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        Log Out
                    </x-responsive-nav-link>
                </form>

            </div>
        </div>

    </div>

</nav>
</div>

<style>
.top-link {
    text-decoration: none;
    color: #6c757d;
    font-weight: 500;
    transition: 0.2s;
}

.top-link:hover {
    color: #0d6efd;
}
</style>