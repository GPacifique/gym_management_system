<nav class="navbar navbar-expand-lg bg-white bg-opacity-75 backdrop-blur shadow-sm sticky-top border-bottom">
    <div class="container-fluid px-4">

        <!-- Brand -->
        <a class="navbar-brand d-flex align-items-center gap-3" href="{{ route('dashboard') }}">
            <div class="bg-primary bg-gradient rounded-3 d-flex align-items-center justify-content-center shadow-sm"
                 style="width:42px;height:42px;">
                <i class="bi bi-activity text-white"></i>
            </div>
            <div class="lh-sm">
                <div class="text-uppercase small text-muted fw-semibold">Gym</div>
                <div class="fw-bold text-dark">Manager Pro</div>
            </div>
        </a>

        <!-- Toggle -->
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <i class="bi bi-list fs-3"></i>
        </button>

        <!-- Content -->
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2 mt-3 mt-lg-0">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a class="nav-link nav-modern {{ request()->is('dashboard') ? 'active-modern' : '' }}"
                       href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2 me-1"></i> Dashboard
                    </a>
                </li>

                <!-- Members -->
                @if(hasRole(['admin', 'manager', 'receptionist']))
                <li class="nav-item">
                    <a class="nav-link nav-modern {{ request()->is('members*') ? 'active-modern' : '' }}"
                       href="{{ url('/members') }}">
                        <i class="bi bi-people me-1"></i> Members
                    </a>
                </li>
                @endif

                <!-- Trainers -->
                @if(hasRole(['admin', 'manager']))
                <li class="nav-item">
                    <a class="nav-link nav-modern {{ request()->is('trainers*') ? 'active-modern' : '' }}"
                       href="{{ url('/trainers') }}">
                        <i class="bi bi-person-workspace me-1"></i> Trainers
                    </a>
                </li>
                @endif

                <!-- Subscriptions -->
                @if(hasRole(['admin', 'manager', 'receptionist']))
                <li class="nav-item">
                    <a class="nav-link nav-modern {{ request()->is('subscriptions*') ? 'active-modern' : '' }}"
                       href="{{ url('/subscriptions') }}">
                        <i class="bi bi-calendar-check me-1"></i> Subscriptions
                    </a>
                </li>
                @endif

                <!-- Payments -->
                @if(hasRole(['admin', 'manager']))
                <li class="nav-item">
                    <a class="nav-link nav-modern {{ request()->is('payments*') ? 'active-modern' : '' }}"
                       href="{{ url('/payments') }}">
                        <i class="bi bi-credit-card me-1"></i> Payments
                    </a>
                </li>
                @endif

                <!-- User -->
                <li class="nav-item dropdown ms-lg-3">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 px-3 py-2 rounded-3 hover-soft"
                       href="#" role="button" data-bs-toggle="dropdown">

                        <!-- Avatar -->
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary fw-bold d-flex align-items-center justify-content-center"
                             style="width:36px;height:36px;">
                            {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                        </div>

                        <span class="fw-semibold text-dark">
                            {{ Auth::user()->name ?? 'Admin' }}
                        </span>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end modern-dropdown mt-2">

                        <li class="px-3 py-2 border-bottom">
                            <div class="fw-semibold text-dark small">
                                {{ Auth::user()->name }}
                            </div>
                            <div class="text-muted small">
                                {{ Auth::user()->email }}
                            </div>
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2"
                               href="{{ url('/profile') }}">
                                <i class="bi bi-person-gear"></i> Profile
                            </a>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="dropdown-item text-danger d-flex align-items-center gap-2">
                                        <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        </li>

                    </ul>
                </li>

            </ul>
        </div>
    </div>
    

</nav>

<style>
/* Glass effect */
.backdrop-blur {
    backdrop-filter: blur(10px);
}

/* Modern nav links */
.nav-modern {
    color: #6c757d !important;
    font-weight: 500;
    padding: 8px 14px;
    border-radius: 10px;
    transition: all 0.25s ease;
}

.nav-modern:hover {
    color: #000 !important;
    background: rgba(0, 0, 0, 0.05);
}

/* Active state */
.active-modern {
    background: rgba(13, 110, 253, 0.1);
    color: #0d6efd !important;
    box-shadow: 0 6px 16px rgba(13,110,253,0.15);
}

/* Dropdown */
.modern-dropdown {
    border: none;
    border-radius: 14px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    overflow: hidden;
}

.dropdown-item {
    padding: 10px 16px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.dropdown-item:hover {
    background: #f8f9fa;
}

/* Soft hover for user */
.hover-soft:hover {
    background: rgba(0,0,0,0.05);
}

/* Mobile */
@media (max-width: 992px) {
    .nav-modern {
        text-align: center;
    }
}
</style>