<!-- USER PROFILE DROPDOWN -->
<li class="nav-item dropdown ms-lg-3">

    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2"
       href="#"
       role="button"
       data-bs-toggle="dropdown">

        <!-- Avatar -->
        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white"
             style="width:38px;height:38px;">
            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
        </div>

        <!-- Name -->
        <div class="d-none d-md-block text-start">
            <div class="fw-semibold text-white">
                {{ Auth::user()->name }}
            </div>
            <small class="text-white-50">
                {{ ucfirst(Auth::user()->role) }}
            </small>
        </div>

    </a>

    <!-- Dropdown Menu -->
    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 p-2">

        <!-- Profile Header -->
        <li class="px-3 py-2">
            <div class="fw-semibold">{{ Auth::user()->name }}</div>
            <small class="text-muted">{{ Auth::user()->email }}</small>
        </li>

        <li><hr class="dropdown-divider"></li>

        <!-- Profile -->
        <li>
            <a class="dropdown-item d-flex align-items-center gap-2"
               href="{{ route('profile.edit') }}">
                <i class="bi bi-person-gear"></i>
                Profile Settings
            </a>
        </li>

        <!-- Gym Context (optional) -->
        <li>
            <a class="dropdown-item d-flex align-items-center gap-2"
               href="{{ route('gyms.index') }}">
                <i class="bi bi-building"></i>
                My Gym
            </a>
        </li>

        <li><hr class="dropdown-divider"></li>

        <!-- Logout -->
        <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="dropdown-item text-danger d-flex align-items-center gap-2">
                    <i class="bi bi-box-arrow-right"></i>
                    Logout
                </button>
            </form>
        </li>

    </ul>
</li>
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* -----------------------------
     * 1. Close dropdown on outside click (extra safety)
     * ----------------------------- */
    document.addEventListener('click', function (e) {
        const dropdowns = document.querySelectorAll('.dropdown-menu.show');

        dropdowns.forEach(menu => {
            const parent = menu.closest('.dropdown');
            if (parent && !parent.contains(e.target)) {
                const toggle = parent.querySelector('[data-bs-toggle="dropdown"]');
                if (toggle) {
                    bootstrap.Dropdown.getOrCreateInstance(toggle).hide();
                }
            }
        });
    });

    /* -----------------------------
     * 2. Smooth hover animation for nav-links
     * ----------------------------- */
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('mouseenter', () => {
            link.style.transition = 'all 0.2s ease';
            link.style.transform = 'translateY(-1px)';
        });

        link.addEventListener('mouseleave', () => {
            link.style.transform = 'translateY(0)';
        });
    });

    /* -----------------------------
     * 3. Auto-collapse mobile navbar after click
     * ----------------------------- */
    const navbarCollapse = document.querySelector('.navbar-collapse');

    if (navbarCollapse) {
        document.querySelectorAll('.navbar-nav .nav-link').forEach(link => {
            link.addEventListener('click', () => {
                if (navbarCollapse.classList.contains('show')) {
                    new bootstrap.Collapse(navbarCollapse).hide();
                }
            });
        });
    }

    /* -----------------------------
     * 4. Add active glow effect (SaaS feel)
     * ----------------------------- */
    document.querySelectorAll('.nav-link.active').forEach(link => {
        link.style.boxShadow = "0 4px 12px rgba(13,110,253,0.3)";
    });

});
</script>