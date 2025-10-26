@props(['active' => ''])

<div class="sidebar bg-dark text-light">
    <div class="sidebar-header p-3 border-bottom border-secondary">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 d-flex align-items-center">
                <img src="{{ asset('images/favicon.svg') }}" alt="GMS" style="height: 24px; margin-right: 10px;">
                @auth
                    @if(Auth::user()->isSuperAdmin())
                        System Admin
                    @else
                        {{ session('gym_id') ? (\App\Models\Gym::find(session('gym_id'))->name ?? 'Select Gym') : 'Select Gym' }}
                    @endif
                @endauth
            </h5>
            @auth
            @if(Auth::user()->isSuperAdmin())
                {{-- Super Admin: Show all gyms --}}
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="bi bi-arrow-left-right"></i>
                        All Gyms
                    </button>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        @foreach(\App\Models\Gym::where('approval_status', 'approved')->orderBy('name')->get() as $g)
                            <li>
                                <a class="dropdown-item d-flex justify-content-between align-items-center" href="{{ route('gyms.switch', $g) }}">
                                    <span>{{ $g->name }}</span>
                                    @if(session('gym_id') == $g->id)
                                        <i class="bi bi-check2 text-success"></i>
                                    @endif
                                </a>
                            </li>
                        @endforeach
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('super-admin.gyms.index') }}"><i class="bi bi-gear me-2"></i>Manage All Gyms</a></li>
                    </ul>
                </div>
            @elseif(Auth::user()->gyms()->count() > 0)
                {{-- Regular users: Show only assigned gyms --}}
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="bi bi-arrow-left-right"></i>
                        Switch Gym
                    </button>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        @php
                            $__gyms = Auth::user()->gyms()->orderBy('name')->get();
                        @endphp
                        @forelse($__gyms as $g)
                        <li>
                            <a class="dropdown-item d-flex justify-content-between align-items-center" href="{{ route('gyms.switch', $g) }}">
                                <span>{{ $g->name }}</span>
                                @if(session('gym_id') == $g->id)
                                    <i class="bi bi-check2 text-success"></i>
                                @endif
                            </a>
                        </li>
                        @empty
                            <li class="px-3 py-2 text-muted small">No gyms assigned</li>
                        @endforelse
                        @if(Auth::user()->isAdmin())
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('gyms.index') }}"><i class="bi bi-gear me-2"></i>Manage My Gyms</a></li>
                        @endif
                    </ul>
                </div>
            @endif
            @endauth
            
            @auth
            @php($currentGymId = session('gym_id'))
            @if($currentGymId)
            {{-- Branch switcher for current gym --}}
            <div class="dropdown mt-2">
                <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="bi bi-diagram-3"></i>
                    Branch: {{ optional(\App\Support\BranchContext::current())->name ?? 'Main Branch' }}
                </button>
                <ul class="dropdown-menu dropdown-menu-dark">
                    @foreach(\App\Models\Branch::where('gym_id', $currentGymId)->orderBy('name')->get() as $branch)
                        <li>
                            <a class="dropdown-item d-flex justify-content-between align-items-center" href="{{ route('branches.switch', $branch) }}">
                                <span>{{ $branch->name }}</span>
                                @if(session('branch_id') == $branch->id)
                                    <i class="bi bi-check2 text-success"></i>
                                @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            @endif
            @endauth
        </div>
    </div>
    
        <div class="sidebar-menu p-2">
        @auth
        <ul class="nav flex-column">
            @if(Auth::user()->isSuperAdmin())
                {{-- Super Admin Menu --}}
                <li class="nav-item">
                    <a href="{{ route('super-admin.dashboard') }}" 
                       class="nav-link {{ $active === 'super-admin.dashboard' ? 'active bg-primary' : 'text-light' }}">
                        <i class="bi bi-speedometer2 me-2"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item mt-2">
                    <div class="px-3 py-2 text-muted small text-uppercase fw-bold">
                        Platform Management
                    </div>
                </li>
                <li class="nav-item">
                    <a href="{{ route('super-admin.gyms.index') }}" 
                       class="nav-link {{ str_starts_with($active, 'super-admin.gyms') ? 'active bg-primary' : 'text-light' }}">
                        <i class="bi bi-building me-2"></i>
                        Gym Accounts
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('super-admin.gyms.index', ['status' => 'pending']) }}" 
                       class="nav-link {{ request()->get('status') === 'pending' ? 'active bg-primary' : 'text-light' }}">
                        <i class="bi bi-clock-history me-2"></i>
                        Pending Approvals
                        @php
                            $pendingCount = \App\Models\Gym::where('approval_status', 'pending')->count();
                        @endphp
                        @if($pendingCount > 0)
                            <span class="badge bg-warning text-dark ms-2">{{ $pendingCount }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('super-admin.gyms.export') }}" 
                       class="nav-link text-light">
                        <i class="bi bi-download me-2"></i>
                        Export Data
                    </a>
                </li>
            @else
                {{-- Regular User Menu --}}
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" 
                       class="nav-link {{ $active === 'dashboard' ? 'active bg-primary' : 'text-light' }}">
                        <i class="bi bi-speedometer2 me-2"></i>
                        Dashboard
                    </a>
                </li>

                @if(Auth::user()->isAdmin())
                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}" 
                       class="nav-link {{ str_starts_with($active, 'admin.users') ? 'active bg-primary' : 'text-light' }}">
                        <i class="bi bi-people-gear me-2"></i>
                        Users
                    </a>
                </li>
                @endif
                
                @if(Auth::user()->hasAnyRole(['admin', 'manager']))
                <li class="nav-item">
                    <a href="{{ route('members.index') }}" 
                       class="nav-link {{ $active === 'members' ? 'active bg-primary' : 'text-light' }}">
                        <i class="bi bi-people-fill me-2"></i>
                        Members
                    </a>
                </li>
                @endif

                @if(Auth::user()->hasAnyRole(['admin', 'manager']))
                @if(session('gym_id') || Auth::user()->default_gym_id)
                <li class="nav-item">
                    <a href="{{ route('gyms.show', session('gym_id') ?? Auth::user()->default_gym_id) }}" 
                       class="nav-link {{ $active === 'gyms.show' ? 'active bg-primary' : 'text-light' }}">
                        <i class="bi bi-building me-2"></i>
                        Gym Profile
                    </a>
                </li>
                @endif
                @endif
                
                @if(Auth::user()->isAdmin())
                <li class="nav-item">
                    <a href="{{ route('trainers.index') }}" 
                       class="nav-link {{ $active === 'trainers' ? 'active bg-primary' : 'text-light' }}">
                        <i class="bi bi-person-workspace me-2"></i>
                        Trainers
                    </a>
                </li>
                @endif
                
                @if(Auth::user()->hasAnyRole(['admin', 'manager']))
                <li class="nav-item">
                    <a href="{{ route('subscriptions.index') }}" 
                       class="nav-link {{ $active === 'subscriptions' ? 'active bg-primary' : 'text-light' }}">
                        <i class="bi bi-calendar-check-fill me-2"></i>
                        Subscriptions
                    </a>
                </li>
                @endif
                
                @if(Auth::user()->hasAnyRole(['admin', 'manager', 'receptionist']))
                <li class="nav-item">
                    <a href="{{ route('payments.index') }}" 
                       class="nav-link {{ $active === 'payments' ? 'active bg-primary' : 'text-light' }}">
                        <i class="bi bi-cash-stack me-2"></i>
                        Payments
                    </a>
                </li>
                @endif
                
                @if(Auth::user()->hasAnyRole(['admin', 'manager']))
                <li class="nav-item">
                    <a href="{{ route('workout-plans.index') }}" 
                       class="nav-link {{ $active === 'workout-plans' ? 'active bg-primary' : 'text-light' }}">
                        <i class="bi bi-calendar2-week me-2"></i>
                        Workout Plans
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('classes.index') }}" 
                       class="nav-link {{ $active === 'classes' ? 'active bg-primary' : 'text-light' }}">
                        <i class="bi bi-collection-fill me-2"></i>
                        Classes
                    </a>
                </li>
                
                {{-- Class Bookings - Available for all authenticated users --}}
                <li class="nav-item">
                    <a href="{{ route('bookings.index') }}" 
                       class="nav-link {{ str_starts_with($active, 'bookings') && !str_contains($active, 'my-bookings') ? 'active bg-primary' : 'text-light' }}">
                        <i class="bi bi-calendar-plus me-2"></i>
                        Book Classes
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('bookings.my-bookings') }}" 
                       class="nav-link {{ str_contains($active, 'my-bookings') ? 'active bg-primary' : 'text-light' }}">
                        <i class="bi bi-bookmark-check me-2"></i>
                        My Bookings
                    </a>
                </li>
                    @endif
                
                @if(Auth::user()->hasAnyRole(['admin', 'manager', 'receptionist']))
                <li class="nav-item">
                    <a href="{{ route('attendances.index') }}" 
                       class="nav-link {{ $active === 'attendances' ? 'active bg-primary' : 'text-light' }}">
                        <i class="bi bi-calendar-check me-2"></i>
                        Attendance
                    </a>
                </li>
                @endif
            @endif
        </ul>
        @endauth
    </div>

    <div class="sidebar-footer border-top border-secondary p-3">
        @auth
        <div class="mb-2 px-2">
            <small class="text-muted">Logged in as</small>
            <div class="badge {{ Auth::user()->isSuperAdmin() ? 'bg-danger' : 'bg-info text-dark' }}">
                {{ Auth::user()->isSuperAdmin() ? 'Super Admin' : ucfirst(Auth::user()->role) }}
            </div>
        </div>
        <div class="dropup-center dropup">
            <button class="btn btn-dark dropdown-toggle d-flex align-items-center w-100" type="button" data-bs-toggle="dropdown">
                @if(Auth::user()->profile_photo)
                    <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="Profile" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover; border: 2px solid #495057;">
                @else
                    <i class="bi bi-person-circle me-2"></i>
                @endif
                {{ Auth::user()->name ?? 'User' }}
            </button>
            <ul class="dropdown-menu dropdown-menu-dark">
                <li>
                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                        <i class="bi bi-gear-fill me-2"></i>
                        Settings
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger" style="cursor: pointer !important; pointer-events: auto !important; width: 100%; text-align: left; border: none; background: transparent;">
                            <i class="bi bi-box-arrow-right me-2"></i>
                            Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
        @endauth
    </div>

    
</div>

<style>
.sidebar {
    width: 250px;
    height: 100vh;
    position: fixed;
    left: 0;
    top: 0;
    display: flex;
    flex-direction: column;
}

.sidebar-menu {
    flex-grow: 1;
    overflow-y: auto;
}

.sidebar-header {
    position: sticky;
    top: 0;
    z-index: 3;
    background: #212529; /* match bg-dark */
}

.sidebar .nav-link {
    border-radius: 4px;
    margin-bottom: 4px;
    transition: all 0.3s ease;
}

.sidebar .nav-link:hover:not(.active) {
    background-color: rgba(255, 255, 255, 0.1);
}

.sidebar-menu::-webkit-scrollbar {
    width: 6px;
}

.sidebar-menu::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
}

.sidebar-menu::-webkit-scrollbar-thumb {
    background-color: rgba(255, 255, 255, 0.2);
    border-radius: 3px;
}

.sidebar-menu::-webkit-scrollbar-thumb:hover {
    background-color: rgba(255, 255, 255, 0.3);
}

/* Hover effect for menu items */
.nav-link {
    position: relative;
    overflow: hidden;
}

.nav-link::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 2px;
    background-color: var(--bs-primary);
    transition: width 0.3s ease;
}

.nav-link:hover::after {
    width: 100%;
}

/* Active state animation */
.nav-link.active {
    transform: translateX(4px);
}

/* Dropdown animations */
.dropdown-menu {
    animation: slideUp 0.2s ease;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>