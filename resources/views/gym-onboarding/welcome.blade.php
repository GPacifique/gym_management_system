@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Progress Steps -->
            <div class="mb-5">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-center flex-fill">
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" 
                             style="width: 50px; height: 50px;">
                            <i class="bi bi-check-lg fs-4"></i>
                        </div>
                        <p class="mt-2 small fw-bold">Welcome</p>
                    </div>
                    <div class="flex-fill" style="height: 2px; background: #dee2e6;"></div>
                    <div class="text-center flex-fill">
                        <div class="rounded-circle bg-light text-muted d-inline-flex align-items-center justify-content-center" 
                             style="width: 50px; height: 50px;">
                            <i class="bi bi-person fs-4"></i>
                        </div>
                        <p class="mt-2 small text-muted">Add Trainer</p>
                    </div>
                    <div class="flex-fill" style="height: 2px; background: #dee2e6;"></div>
                    <div class="text-center flex-fill">
                        <div class="rounded-circle bg-light text-muted d-inline-flex align-items-center justify-content-center" 
                             style="width: 50px; height: 50px;">
                            <i class="bi bi-card-checklist fs-4"></i>
                        </div>
                        <p class="mt-2 small text-muted">Plans</p>
                    </div>
                    <div class="flex-fill" style="height: 2px; background: #dee2e6;"></div>
                    <div class="text-center flex-fill">
                        <div class="rounded-circle bg-light text-muted d-inline-flex align-items-center justify-content-center" 
                             style="width: 50px; height: 50px;">
                            <i class="bi bi-rocket fs-4"></i>
                        </div>
                        <p class="mt-2 small text-muted">Launch</p>
                    </div>
                </div>
            </div>

            <!-- Welcome Card -->
            <div class="card shadow-lg border-0">
                <div class="card-body p-5 text-center">
                    <div class="mb-4">
                        <i class="bi bi-building text-success" style="font-size: 5rem;"></i>
                    </div>
                    
                    <h1 class="display-5 fw-bold mb-3">Welcome to {{ $gym->name }}!</h1>
                    
                    <p class="lead text-muted mb-4">
                        Congratulations on registering your gym! Let's get you set up in just a few steps.
                    </p>

                    <div class="alert alert-info mb-4">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Email Verification:</strong> We've sent a verification email to <strong>{{ $gym->email }}</strong>. 
                        Please check your inbox and verify your email address.
                    </div>

                    <div class="row g-4 mb-5">
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded">
                                <i class="bi bi-calendar-check text-primary fs-3 mb-2"></i>
                                <h5 class="mb-0">30-Day Trial</h5>
                                <small class="text-muted">Full access to all features</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded">
                                <i class="bi bi-shield-check text-success fs-3 mb-2"></i>
                                <h5 class="mb-0">No Credit Card</h5>
                                <small class="text-muted">Required to start</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded">
                                <i class="bi bi-clock text-warning fs-3 mb-2"></i>
                                <h5 class="mb-0">5 Minutes</h5>
                                <small class="text-muted">To complete setup</small>
                            </div>
                        </div>
                    </div>

                    <h4 class="mb-3">What's Next?</h4>
                    <p class="text-muted mb-4">
                        We'll guide you through adding your first trainer and setting up membership plans. 
                        Don't worry, you can always add more details later!
                    </p>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <a href="{{ route('gym.onboarding.add-trainer') }}" class="btn btn-primary btn-lg px-5">
                            <i class="bi bi-arrow-right me-2"></i>Let's Get Started
                        </a>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-lg">
                            Skip to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
