@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Progress indicator showing step 3 active -->
            <div class="mb-5">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-center flex-fill">
                        <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-check-lg"></i>
                        </div>
                        <p class="mt-2 small text-success">Welcome</p>
                    </div>
                    <div class="flex-fill" style="height: 2px; background: #198754;"></div>
                    <div class="text-center flex-fill">
                        <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-check-lg"></i>
                        </div>
                        <p class="mt-2 small text-success">Trainer</p>
                    </div>
                    <div class="flex-fill" style="height: 2px; background: #0d6efd;"></div>
                    <div class="text-center flex-fill">
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-card-checklist"></i>
                        </div>
                        <p class="mt-2 small fw-bold">Plans</p>
                    </div>
                    <div class="flex-fill" style="height: 2px; background: #dee2e6;"></div>
                    <div class="text-center flex-fill">
                        <div class="rounded-circle bg-light text-muted d-inline-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-rocket"></i>
                        </div>
                        <p class="mt-2 small text-muted">Launch</p>
                    </div>
                </div>
            </div>

            <div class="card shadow-lg border-0">
                <div class="card-body p-5 text-center">
                    <i class="bi bi-card-checklist text-primary" style="font-size: 3rem;"></i>
                    <h2 class="mt-3">Membership Plans</h2>
                    <p class="text-muted mb-4">You can create detailed membership plans from your dashboard</p>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Coming Soon:</strong> Set up flexible membership plans, pricing tiers, and subscription options.
                    </div>

                    <p class="mb-4">For now, let's get you to your dashboard where you can explore all features!</p>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <form method="POST" action="{{ route('gym.onboarding.skip-membership-plans') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="bi bi-arrow-right me-2"></i>Continue to Dashboard
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
