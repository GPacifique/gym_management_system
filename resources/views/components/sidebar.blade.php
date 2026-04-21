@props(['active' => ''])

<div x-data="{ open: false }" class="md:pl-64">

    <!-- Mobile Top Bar -->
    <div class="md:hidden flex items-center justify-between bg-gray-900 text-white px-4 py-3 shadow">
        <div class="flex items-center gap-3">
            <button @click="open = !open" class="p-2 rounded-lg hover:bg-gray-800">
                <i class="bi bi-list text-lg"></i>
            </button>

            <div class="flex items-center gap-2">
                <img src="{{ asset('images/favicon.svg') }}" class="h-6">
                <span class="font-semibold">{{ config('app.name') }}</span>
            </div>
        </div>

        @auth
            <div class="text-sm text-gray-300">
                {{ Auth::user()->name }}
            </div>
        @endauth
    </div>

    <!-- Sidebar -->
    <aside :class="open ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-950 text-gray-300 transform md:translate-x-0 transition-all duration-300 shadow-xl">

        

            <!-- Nav -->
            <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-6">

                @auth

                <!-- Dashboard -->
                <div class="nav-title" ></div>
                    <a href="{{ route('dashboard') }}"
                       class="nav-item {{ $active === 'dashboard' ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>
                        Dashboard
                    </a>
                

                <!-- PLATFORM -->
                @role('super_admin')
                <div>
                    <div class="nav-title">Platform</div>

                    <a href="{{ route('super-admin.gyms.index') }}"
                       class="nav-item {{ str_starts_with($active,'super-admin.gyms') ? 'active' : '' }}">
                        <i class="bi bi-building"></i>
                        Gym Accounts
                    </a>

                    <a href="{{ route('super-admin.gyms.index', ['status'=>'pending']) }}"
                       class="nav-item">
                        <i class="bi bi-clock-history"></i>
                        Pending Approvals
                    </a>
                </div>
                @endrole

                <!-- GYM -->
                @role(['admin','manager'])
                <div>
                    <div class="nav-title">Gym</div>

                    <a href="{{ route('gyms.index') }}"
                       class="nav-item {{ str_starts_with($active,'gyms') ? 'active' : '' }}">
                        <i class="bi bi-building"></i>
                        My Gyms
                    </a>

                    <a href="{{ session('gym_id') ? route('gyms.show', session('gym_id')) : route('gyms.index') }}"
                       class="nav-item">
                        <i class="bi bi-card-list"></i>
                        Gym Profile
                    </a>
                </div>
                @endrole

                <!-- PEOPLE -->
                @role(['admin','manager'])
                <div>
                    <div class="nav-title">People</div>

                    <a href="{{ route('admin.users.index') }}" class="nav-item">
                        <i class="bi bi-people-gear"></i>
                        Users
                    </a>
                </div>
                @endrole

                @role(['admin','manager','receptionist'])
                <div>
                    <a href="{{ route('members.index') }}" class="nav-item">
                        <i class="bi bi-people-fill"></i>
                        Members
                    </a>
                </div>
                @endrole

                @role(['admin','manager'])
                <div>
                    <a href="{{ route('trainers.index') }}" class="nav-item">
                        <i class="bi bi-person-workspace"></i>
                        Trainers
                    </a>
                </div>
                @endrole

                <!-- OPERATIONS -->
                @role(['admin','manager','receptionist'])
                <div>
                    <div class="nav-title">Operations</div>

                    <a href="{{ route('subscriptions.index') }}" class="nav-item">
                        <i class="bi bi-calendar-check"></i>
                        Subscriptions
                    </a>
                </div>
                @endrole

                @role(['admin','manager'])
                <a href="{{ route('payments.index') }}" class="nav-item">
                    <i class="bi bi-cash-stack"></i>
                    Payments
                </a>
                @endrole

                @role(['admin','manager'])
                <a href="{{ route('workout-plans.index') }}" class="nav-item">
                    <i class="bi bi-calendar2-week"></i>
                    Workout Plans
                </a>
                @endrole

                @role(['admin','manager'])
                <a href="{{ route('classes.index') }}" class="nav-item">
                    <i class="bi bi-collection"></i>
                    Classes
                </a>
                @endrole

                <!-- BOOKINGS -->
                @auth
                <div>
                    <div class="nav-title">Bookings</div>

                    <a href="{{ route('bookings.index') }}" class="nav-item">
                        <i class="bi bi-calendar-plus"></i>
                        Book Classes
                    </a>

                    <a href="{{ route('bookings.my-bookings') }}" class="nav-item">
                        <i class="bi bi-bookmark-check"></i>
                        My Bookings
                    </a>

                    <a href="{{ route('attendances.index') }}" class="nav-item">
                        <i class="bi bi-calendar-check"></i>
                        Attendance
                    </a>
                </div>
                @endauth

                @endauth
            </nav>

        </div>
    </aside>
</div>

<style>
.nav-title {
    font-size: 14px;
    text-transform: uppercase;
    color: #527cd1;
    margin-bottom: 6px;
    padding: 0 10px;
}

.nav-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    border-radius: 10px;
    color: #1f3457;
    font-size: 14px;
    transition: all 0.2s ease;
}

.nav-item:hover {
    background: rgba(255,255,255,0.05);
    color: #181313be;
}

.nav-item.active {
    background: rgba(99,102,241,0.15);
    color: #2e8a2a;
}

.btn-soft {
    padding: 5px 10px;
    border: 1px solid #374151;
    border-radius: 6px;
    font-size: 12px;
    color: #e5e7eb;
}

.btn-soft:hover {
    background: #1f2937;
}

.btn-danger {
    background: #dc2626;
    border: none;
    padding: 5px 10px;
    border-radius: 6px;
    font-size: 12px;
    color: white;
}

.btn-danger:hover {
    background: #b91c1c;
}
</style>