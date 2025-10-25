<!-- resources/views/layouts/header.blade.php -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm sticky-top">
    <div class="container-fluid px-4">
        <!-- Logo / Brand -->
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/dashboard') }}">
            <i class="bi bi-activity fs-3 text-primary me-2"></i>
            <span class="fw-bold text-uppercase">Gym Manager</span>
        </a>

        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Links -->
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-house-door me-1"></i> Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('members*') ? 'active' : '' }}" href="{{ url('/members') }}">
                        <i class="bi bi-people me-1"></i> Members
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('trainers*') ? 'active' : '' }}" href="{{ url('/trainers') }}">
                        <i class="bi bi-person-workspace me-1"></i> Trainers
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('subscriptions*') ? 'active' : '' }}" href="{{ url('/subscriptions') }}">
                        <i class="bi bi-calendar-check me-1"></i> Subscriptions
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('payments*') ? 'active' : '' }}" href="{{ url('/payments') }}">
                        <i class="bi bi-credit-card me-1"></i> Payments
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name ?? 'Admin' }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="{{ url('/profile') }}"><i class="bi bi-gear me-2"></i>Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Optional custom styling -->
<style>
    .navbar {
        transition: all 0.3s ease;
    }

    .navbar-brand span {
        letter-spacing: 1px;
    }

    .navbar-nav .nav-link {
        color: #e9ecef !important;
        margin-right: 0.5rem;
        font-weight: 500;
        transition: color 0.2s ease;
    }

    .navbar-nav .nav-link:hover,
    .navbar-nav .nav-link.active {
        color: #0d6efd !important;
    }

    @media (max-width: 992px) {
        .navbar-nav .nav-link {
            margin-right: 0;
            padding: 0.75rem 1rem;
            text-align: center;
        }
    }
</style>
