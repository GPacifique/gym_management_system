<!-- resources/views/layouts/header.blade.php -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm sticky-top border-bottom border-secondary-subtle">
    <div class="container-fluid px-4">

        <!-- Logo / Brand -->
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
            <div class="bg-primary bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                <i class="bi bi-activity text-white fs-5"></i>
            </div>
            <div>
                <div class="fw-bold text-uppercase small text-white-50">Gym</div>
                <div class="fw-bold text-white">Manager Pro</div>
            </div>
        </a>

        <!-- Mobile Toggle -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Content -->
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">

                {{-- Dashboard (Everyone) --}}
                <li class="nav-item">
                    <a class="nav-link px-3 rounded {{ request()->is('dashboard') ? 'active bg-primary text-white' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2 me-1"></i> Dashboard
                    </a>
                </li>

                {{-- Members --}}
                @if(hasRole(['admin', 'manager', 'receptionist']))
                <li class="nav-item">
                    <a class="nav-link px-3 rounded {{ request()->is('members*') ? 'active bg-primary text-white' : '' }}" href="{{ url('/members') }}">
                        <i class="bi bi-people me-1"></i> Members
                    </a>
                </li>
                @endif

                {{-- Trainers --}}
                @if(hasRole(['admin', 'manager']))
                <li class="nav-item">
                    <a class="nav-link px-3 rounded {{ request()->is('trainers*') ? 'active bg-primary text-white' : '' }}" href="{{ url('/trainers') }}">
                        <i class="bi bi-person-workspace me-1"></i> Trainers
                    </a>
                </li>
                @endif

                {{-- Subscriptions --}}
                @if(hasRole(['admin', 'manager', 'receptionist']))
                <li class="nav-item">
                    <a class="nav-link px-3 rounded {{ request()->is('subscriptions*') ? 'active bg-primary text-white' : '' }}" href="{{ url('/subscriptions') }}">
                        <i class="bi bi-calendar-check me-1"></i> Subscriptions
                    </a>
                </li>
                @endif

                {{-- Payments --}}
                @if(hasRole(['admin', 'manager']))
                <li class="nav-item">
                    <a class="nav-link px-3 rounded {{ request()->is('payments*') ? 'active bg-primary text-white' : '' }}" href="{{ url('/payments') }}">
                        <i class="bi bi-credit-card me-1"></i> Payments
                    </a>
                </li>
                @endif

                <!-- User Dropdown -->
                <li class="nav-item dropdown ms-lg-3">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown">
                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" style="width:35px;height:35px;">
                            <i class="bi bi-person text-white"></i>
                        </div>
                        <span class="fw-semibold">{{ Auth::user()->name ?? 'Admin' }}</span>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2" href="{{ url('/profile') }}">
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
    .navbar {
        backdrop-filter: blur(10px);
    }

    .nav-link {
        color: #adb5bd !important;
        font-weight: 500;
        transition: all 0.25s ease;
    }

    .nav-link:hover {
        color: #ffffff !important;
        background: rgba(255,255,255,0.05);
    }

    .nav-link.active {
        box-shadow: 0 4px 12px rgba(13,110,253,0.3);
    }

    .dropdown-menu {
        border-radius: 12px;
        overflow: hidden;
    }

    .dropdown-item {
        padding: 10px 15px;
        font-weight: 500;
    }

    .dropdown-item:hover {
        background-color: #f1f3f5;
    }

    @media (max-width: 992px) {
        .nav-link {
            text-align: center;
        }
    }
</style>