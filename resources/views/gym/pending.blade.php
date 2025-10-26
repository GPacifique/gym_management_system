<x-app-layout>
    <div class="container py-5 main-content">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg border-0">
                    <div class="card-header text-white" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                        <h5 class="mb-0"><i class="bi bi-shield-exclamation me-2"></i>Gym Account Pending Approval</h5>
                    </div>
                    <div class="card-body p-4">
                        <p class="lead mb-3">Thanks for registering your gym with GymMate.</p>
                        <p class="mb-4">Your account is currently <span class="badge bg-warning text-dark">Pending</span> approval by a System Administrator. You'll receive an email notification once it's approved.</p>

                        <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>You can still update your profile information.</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>You cannot access admin features until approval.</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>If you need urgent access, please contact support.</li>
                        </ul>

                        <div class="d-flex gap-2">
                            <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary">
                                <i class="bi bi-gear me-1"></i> Update Profile
                            </a>
                            <a href="{{ route('logout') }}" class="btn btn-outline-secondary"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right me-1"></i> Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info mt-3" role="alert">
                    <i class="bi bi-info-circle me-2"></i>
                    If you believe this is a mistake, contact the System Administrator.
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
