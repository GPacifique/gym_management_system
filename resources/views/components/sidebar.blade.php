@props(['active' => ''])

<div x-data="{ open: false }" class="md:pl-64">
    <!-- Mobile top bar with toggle -->
    <div class="md:hidden flex items-center justify-between bg-gray-800 text-gray-100 px-4 py-2">
        <div class="flex items-center gap-3">
            <button @click="open = !open" class="p-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M3 5h14a1 1 0 010 2H3a1 1 0 010-2zm0 4h14a1 1 0 010 2H3a1 1 0 010-2zm0 4h14a1 1 0 010 2H3a1 1 0 010-2z" clip-rule="evenodd" />
                </svg>
            </button>
            <div class="flex items-center">
                <img src="{{ asset('images/favicon.svg') }}" alt="GMS" class="h-6 mr-2">
                <span class="font-semibold">{{ config('app.name', 'Gymmate') }}</span>
            </div>
        </div>
        @auth
            <div class="text-sm text-gray-300">{{ Auth::user()->name }}</div>
        @endauth
    </div>

    <!-- Sidebar -->
    <aside :class="open ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-900 text-gray-100 transform md:translate-x-0 transition-transform duration-200 ease-in-out shadow-lg">
        <div class="h-full flex flex-col">
            <div class="px-4 py-4 border-b border-gray-800 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/favicon.svg') }}" alt="GMS" class="h-6">
                    <div class="text-lg font-semibold">{{ config('app.name', 'Gymmate') }}</div>
                </div>
                <div class="md:hidden">
                    <button @click="open = false" class="p-1 rounded-md hover:bg-gray-800 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M6.293 4.293a1 1 0 011.414 0L10 6.586l2.293-2.293a1 1 0 111.414 1.414L11.414 8l2.293 2.293a1 1 0 01-1.414 1.414L10 9.414l-2.293 2.293a1 1 0 01-1.414-1.414L8.586 8 6.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>

            <nav class="flex-1 overflow-y-auto px-2 py-4">
                @auth
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium {{ $active === 'dashboard' ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                            <i class="bi bi-speedometer2"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li class="pt-3">
                        <div class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Platform</div>
                    </li>
                    <li>
                        <a href="{{ route('super-admin.gyms.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium {{ str_starts_with($active, 'super-admin.gyms') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                            <i class="bi bi-building"></i>
                            <span>Gym Accounts</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('super-admin.gyms.index', ['status' => 'pending']) }}" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm text-gray-300 hover:bg-gray-800">
                            <i class="bi bi-clock-history"></i>
                            <span>Pending Approvals</span>
                        </a>
                    </li>

                    <li class="pt-3">
                        <div class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Gym</div>
                    </li>
                    <li>
                        <a href="{{ route('gyms.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium {{ str_starts_with($active, 'gyms') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                            <i class="bi bi-building"></i>
                            <span>My Gyms</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ session('gym_id') ? route('gyms.show', session('gym_id')) : route('gyms.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm text-gray-300 hover:bg-gray-800">
                            <i class="bi bi-card-list"></i>
                            <span>Gym Profile</span>
                        </a>
                    </li>
                    @if (Route::has('branches.index'))
                    <li>
                        <a href="{{ route('branches.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm text-gray-300 hover:bg-gray-800">
                            <i class="bi bi-diagram-3"></i>
                            <span>Branches</span>
                        </a>
                    </li>
                    @endif

                    <li class="pt-3">
                        <div class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">People</div>
                    </li>
                    <li>
                        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm text-gray-300 hover:bg-gray-800">
                            <i class="bi bi-people-gear"></i>
                            <span>Users</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('members.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm text-gray-300 hover:bg-gray-800">
                            <i class="bi bi-people-fill"></i>
                            <span>Members</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('trainers.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm text-gray-300 hover:bg-gray-800">
                            <i class="bi bi-person-workspace"></i>
                            <span>Trainers</span>
                        </a>
                    </li>

                    <li class="pt-3">
                        <div class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Operations</div>
                    </li>
                    <li>
                        <a href="{{ route('subscriptions.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm text-gray-300 hover:bg-gray-800">
                            <i class="bi bi-calendar-check-fill"></i>
                            <span>Subscriptions</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('payments.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm text-gray-300 hover:bg-gray-800">
                            <i class="bi bi-cash-stack"></i>
                            <span>Payments</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('workout-plans.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm text-gray-300 hover:bg-gray-800">
                            <i class="bi bi-calendar2-week"></i>
                            <span>Workout Plans</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('classes.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm text-gray-300 hover:bg-gray-800">
                            <i class="bi bi-collection-fill"></i>
                            <span>Classes</span>
                        </a>
                    </li>

                    <li class="pt-3">
                        <div class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Bookings</div>
                    </li>
                    <li>
                        <a href="{{ route('bookings.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm text-gray-300 hover:bg-gray-800">
                            <i class="bi bi-calendar-plus"></i>
                            <span>Book Classes</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('bookings.my-bookings') }}" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm text-gray-300 hover:bg-gray-800">
                            <i class="bi bi-bookmark-check"></i>
                            <span>My Bookings</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('attendances.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm text-gray-300 hover:bg-gray-800">
                            <i class="bi bi-calendar-check"></i>
                            <span>Attendance</span>
                        </a>
                    </li>
                </ul>
                @endauth
            </nav>

            <div class="px-4 py-4 border-t border-gray-800">
                @auth
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs text-gray-400">Signed in</div>
                        <div class="text-sm text-gray-200">{{ Auth::user()->email }}</div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('profile.edit') }}" class="px-3 py-1 rounded-md text-sm bg-transparent border border-gray-700 text-gray-200 hover:bg-gray-800">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="px-3 py-1 rounded-md text-sm bg-red-600 hover:bg-red-700 text-white">Logout</button>
                        </form>
                    </div>
                </div>
                @else
                <div class="text-center">
                    <a href="{{ route('login') }}" class="px-3 py-1 rounded-md text-sm bg-transparent border border-gray-700 text-gray-200 hover:bg-gray-800">Login</a>
                </div>
                @endauth
            </div>
        </div>
    </aside>
</div>
