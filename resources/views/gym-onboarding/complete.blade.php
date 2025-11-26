@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Progress indicator showing all complete -->
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
                    <div class="flex-fill" style="height: 2px; background: #198754;"></div>
                    <div class="text-center flex-fill">
                        <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-check-lg"></i>
                        </div>
                        <p class="mt-2 small text-success">Plans</p>
                    </div>
                    <div class="flex-fill" style="height: 2px; background: #198754;"></div>
                    <div class="text-center flex-fill">
                        <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-rocket"></i>
                        </div>
                        <p class="mt-2 small text-success fw-bold">Launch</p>
                    </div>
                </div>
            </div>

            <div class="card shadow-lg border-0">
                <div class="card-body p-5 text-center">
                    <div class="text-success mb-4" style="font-size: 5rem;">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <h1 class="mb-3">🎉 You're All Set!</h1>
                    <p class="lead text-muted mb-4">Welcome to your gym management dashboard</p>

                    <div class="row g-4 mb-5">
                        <div class="col-md-4">
                            <div class="p-3">
                                <i class="bi bi-people-fill text-primary" style="font-size: 2rem;"></i>
                                <h5 class="mt-2">Manage Members</h5>
                                <p class="small text-muted">Track memberships and attendance</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3">
                                <i class="bi bi-calendar-event text-success" style="font-size: 2rem;"></i>
                                <h5 class="mt-2">Schedule Classes</h5>
                                <p class="small text-muted">Plan and manage group classes</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3">
                                <i class="bi bi-graph-up text-info" style="font-size: 2rem;"></i>
                                <h5 class="mt-2">Track Progress</h5>
                                <p class="small text-muted">Monitor gym performance</p>
                            </div>
                        </div>
                    </div>

                    @if (auth()->check() && optional(auth()->user()->currentGym)->hasVerifiedEmail() === false)
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Don't forget:</strong> Check your email to verify your account and unlock all features.
                    </div>
                    @endif

                    <form method="POST" action="{{ route('gym.onboarding.finish') }}">
                        @csrf
                        <button type="submit" class="btn btn-success btn-lg px-5">
                            <i class="bi bi-rocket-takeoff me-2"></i>Go to Dashboard
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
