@extends('layouts.guest')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-6 text-center">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <div class="mb-4">
                        <i class="bi bi-shield-lock text-danger" style="font-size: 5rem;"></i>
                    </div>
                    <h1 class="display-4 fw-bold text-danger mb-3">403</h1>
                    <h2 class="h4 mb-3">Access Denied</h2>
                    <p class="text-muted mb-4">
                        @if(request()->is('members*'))
                            <strong>You tried to access: Member Management</strong><br>
                        @elseif(request()->is('subscriptions*'))
                            <strong>You tried to access: Subscription Management</strong><br>
                        @elseif(request()->is('classes*'))
                            <strong>You tried to access: Class Management</strong><br>
                        @elseif(request()->is('trainers*'))
                            <strong>You tried to access: Trainer Management</strong><br>
                        @elseif(request()->is('workout-plans*'))
                            <strong>You tried to access: Workout Plans</strong><br>
                        @elseif(request()->is('admin*'))
                            <strong>You tried to access: Admin Panel</strong><br>
                        @elseif(request()->is('gyms*'))
                            <strong>You tried to access: Gym Management</strong><br>
                        @elseif(request()->is('payments/*/edit'))
                            <strong>You tried to: Edit a Payment</strong><br>
                        @elseif(request()->is('attendances/*/edit'))
                            <strong>You tried to: Edit an Attendance Record</strong><br>
                        @endif
                        Sorry, you don't have permission to access this resource.
                        @auth
                            Your current role (<strong>{{ ucfirst(Auth::user()->role) }}</strong>) does not have the required permissions.
                            
                            @if(Auth::user()->role === 'receptionist')
                                <br><br>
                                <span class="text-info">
                                    <i class="bi bi-info-circle"></i> As a <strong>Receptionist</strong>, you can:
                                </span>
                                <ul class="text-start mt-2 d-inline-block">
                                    <li><i class="bi bi-check text-success"></i> View and record payments</li>
                                    <li><i class="bi bi-check text-success"></i> Track attendance (check-in/check-out)</li>
                                    <li><i class="bi bi-check text-success"></i> Export attendance reports</li>
                                </ul>
                                <br>
                                <small class="text-muted">
                                    <i class="bi bi-x-circle text-danger"></i> You cannot edit members, subscriptions, or delete records.
                                </small>
                            @endif
                        @endauth
                    </p>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-primary">
                                <i class="bi bi-speedometer2"></i> Go to Dashboard
                            </a>
                            @if(Auth::user()->role === 'receptionist')
                                <a href="{{ route('payments.index') }}" class="btn btn-success">
                                    <i class="bi bi-cash"></i> Payments
                                </a>
                                <a href="{{ route('attendances.index') }}" class="btn btn-info">
                                    <i class="bi bi-calendar-check"></i> Attendance
                                </a>
                            @endif
                            <a href="{{ route('welcome') }}" class="btn btn-outline-primary">
                                <i class="bi bi-house"></i> Home
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-success">
                                <i class="bi bi-person-plus"></i> Register
                            </a>
                            <a href="{{ route('welcome') }}" class="btn btn-outline-primary">
                                <i class="bi bi-house"></i> Home
                            </a>
                        @endauth
                        <a href="javascript:history.back()" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Go Back
                        </a>
                    </div>

                    @auth
                    <div class="mt-4 p-3 bg-light rounded">
                        <h6 class="text-muted mb-2">Need Access?</h6>
                        <p class="small mb-0">
                            Contact your system administrator to request appropriate permissions for your role.
                        </p>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
