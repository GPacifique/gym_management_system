<x-app-layout>
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="mb-2">Welcome to GymMate</h4>
                <p class="text-muted">Your account currently has member access. In future, you'll be able to view your subscription, workout plans, payments, and attendance here.</p>

                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="text-muted">Active Subscriptions</div>
                                <div class="h4 mb-0">{{ $activeSubscriptions ?? 0 }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="text-muted">Total Members</div>
                                <div class="h4 mb-0">{{ $memberCount ?? 0 }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-warning mt-4" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Want a personal portal? We can add a self-service area to view your own plans and history.
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
