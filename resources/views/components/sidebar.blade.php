@props(['active' => ''])

<div class="sidebar bg-dark text-light">
    <div class="sidebar-header p-3 border-bottom border-secondary">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 d-flex align-items-center">
                <img src="{{ asset('images/favicon.svg') }}" alt="GMS" style="height: 24px; margin-right: 10px;">
                {{ config('app.name', 'Gymmate') }}
            </h5>
            @auth
                <div class="d-flex align-items-center">
                    <small class="text-muted me-3">{{ Auth::user()->name }}</small>
                </div>
            @endauth
        </div>
    </div>

    <div class="sidebar-menu p-2">
        @auth
            <ul class="nav flex-column">
                {{-- Dashboard --}}
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ $active === 'dashboard' ? 'active bg-primary' : 'text-light' }}">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>

                {{-- Platform / Super Admin --}}
                <li class="nav-item mt-3">
                    <div class="px-3 py-1 text-muted small text-uppercase fw-bold">Platform Management</div>
                </li>
                <li class="nav-item">
                    <a href="{{ route('super-admin.gyms.index') }}" class="nav-link {{ str_starts_with($active, 'super-admin.gyms') ? 'active bg-primary' : 'text-light' }}">
                        <i class="bi bi-building me-2"></i> Gym Accounts
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('super-admin.gyms.index', ['status' => 'pending']) }}" class="nav-link text-light">
                        <i class="bi bi-clock-history me-2"></i> Pending Approvals
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('super-admin.gyms.export') }}" class="nav-link text-light">
                        <i class="bi bi-download me-2"></i> Export Data
                    </a>
                </li>

                {{-- Gym Management --}}
                <li class="nav-item mt-3">
                    <div class="px-3 py-1 text-muted small text-uppercase fw-bold">Gym Management</div>
                </li>
                <li class="nav-item">
                    <a href="{{ route('gyms.index') }}" class="nav-link {{ str_starts_with($active, 'gyms') ? 'active bg-primary' : 'text-light' }}">
                        <i class="bi bi-building me-2"></i> My Gyms
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ session('gym_id') ? route('gyms.show', session('gym_id')) : route('gyms.index') }}" class="nav-link text-light">
                        <i class="bi bi-card-list me-2"></i> Gym Profile
                    </a>
                </li>
                @if (Route::has('branches.index'))
                <li class="nav-item">
                    <a href="{{ route('branches.index') }}" class="nav-link text-light">
                        <i class="bi bi-diagram-3 me-2"></i> Branches
                    </a>
                </li>
                @endif

                {{-- People & Roles --}}
                <li class="nav-item mt-3">
                    <div class="px-3 py-1 text-muted small text-uppercase fw-bold">People</div>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}" class="nav-link text-light">
                        <i class="bi bi-people-gear me-2"></i> Users
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('members.index') }}" class="nav-link text-light">
                        <i class="bi bi-people-fill me-2"></i> Members
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('trainers.index') }}" class="nav-link text-light">
                        <i class="bi bi-person-workspace me-2"></i> Trainers
                    </a>
                </li>

                {{-- Operations --}}
                <li class="nav-item mt-3">
                    <div class="px-3 py-1 text-muted small text-uppercase fw-bold">Operations</div>
                </li>
                <li class="nav-item">
                    <a href="{{ route('subscriptions.index') }}" class="nav-link text-light">
                        <i class="bi bi-calendar-check-fill me-2"></i> Subscriptions
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('payments.index') }}" class="nav-link text-light">
                        <i class="bi bi-cash-stack me-2"></i> Payments
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('workout-plans.index') }}" class="nav-link text-light">
                        <i class="bi bi-calendar2-week me-2"></i> Workout Plans
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('classes.index') }}" class="nav-link text-light">
                        <i class="bi bi-collection-fill me-2"></i> Classes
                    </a>
                </li>

                {{-- Bookings & Attendance --}}
                <li class="nav-item">
                    <a href="{{ route('bookings.index') }}" class="nav-link text-light">
                        <i class="bi bi-calendar-plus me-2"></i> Book Classes
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('bookings.my-bookings') }}" class="nav-link text-light">
                        <i class="bi bi-bookmark-check me-2"></i> My Bookings
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('attendances.index') }}" class="nav-link text-light">
                        <i class="bi bi-calendar-check me-2"></i> Attendance
                    </a>
                </li>
            </ul>
        @endauth
    </div>

    <div class="sidebar-footer border-top border-secondary p-3">
        @auth
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">Signed in</small>
                    <div class="text-light small">{{ Auth::user()->email }}</div>
                </div>
                <div>
                    <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-light me-2">Profile</a>
                    <form method="POST" action="{{ route('logout') }}" style="display:inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger">Logout</button>
                    </form>
                </div>
            </div>
        @else
            <div class="text-center">
                <a href="{{ route('login') }}" class="btn btn-sm btn-outline-light">Login</a>
            </div>
        @endauth
    </div>

</div>

<style>
/* minimal sidebar styles */
.sidebar { width: 250px; height: 100vh; position: fixed; left: 0; top: 0; display:flex; flex-direction:column; }
.sidebar-menu { flex-grow:1; overflow-y:auto; padding-bottom:10px; }
.sidebar .nav-link { border-radius:4px; margin-bottom:4px; }
.sidebar .nav-link:hover:not(.active) { background-color: rgba(255,255,255,0.04); }
.sidebar-header { position: sticky; top:0; z-index:3; background: #212529; }
.sidebar-footer { margin-top: auto; }
</style>
