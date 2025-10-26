<x-app-layout>
    <div class="container mt-5">
        @php($__gym = \App\Models\Gym::find(session('gym_id') ?? Auth::user()->default_gym_id))
        <div class="row mb-3">
            <div class="col-12">
                <h3 class="fw-bold mb-0">
                    <i class="bi bi-building me-2"></i>{{ $__gym?->name ?? 'Select Gym' }}
                    <small class="text-muted ms-2">Dashboard</small>
                </h3>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="mb-2">Welcome, Trainer</h4>
                <p class="text-muted mb-4">Your role currently has limited access. Contact an administrator to link your user to a trainer profile to enable class and member insights.</p>
                <div class="alert alert-info d-flex align-items-center" role="alert">
                    <i class="bi bi-info-circle me-2"></i>
                    You can access the dashboard summary and navigate to view classes if available.
                </div>
                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="text-muted">Members</div>
                                <div class="h4 mb-0">{{ $memberCount ?? 0 }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="text-muted">Today's Check-ins</div>
                                <div class="h4 mb-0">{{ $todayCheckIns ?? 0 }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
