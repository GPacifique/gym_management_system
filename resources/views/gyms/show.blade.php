@extends('layouts.app')
@section('title', $gym->name . ' Profile')

@section('content')
<div class="container mt-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Gym Header -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-2 text-center">
                    @if($gym->logo)
                        <img src="{{ asset('storage/' . $gym->logo) }}" alt="{{ $gym->name }}" class="img-fluid rounded" style="max-height: 120px;">
                    @else
                        <div class="bg-primary text-white rounded d-flex align-items-center justify-content-center" style="height: 120px;">
                            <i class="bi bi-building" style="font-size: 3rem;"></i>
                        </div>
                    @endif
                </div>
                <div class="col-md-8">
                    <h2 class="mb-2">{{ $gym->name }}</h2>
                    <div class="text-muted mb-3">
                        @if($gym->address)
                            <div><i class="bi bi-geo-alt-fill me-2"></i>{{ $gym->address }}</div>
                        @endif
                        @if($gym->phone)
                            <div><i class="bi bi-telephone-fill me-2"></i>{{ $gym->phone }}</div>
                        @endif
                        @if($gym->email)
                            <div><i class="bi bi-envelope-fill me-2"></i><a href="mailto:{{ $gym->email }}">{{ $gym->email }}</a></div>
                        @endif
                        @if($gym->website)
                            <div><i class="bi bi-globe me-2"></i><a href="{{ $gym->website }}" target="_blank">{{ $gym->website }}</a></div>
                        @endif
                    </div>
                    @if($gym->description)
                        <p class="mb-0">{{ $gym->description }}</p>
                    @endif
                </div>
                <div class="col-md-2 text-end">
                    @if(Auth::user()->isAdmin() || Auth::user()->hasRoleInGym('manager', $gym->id))
                        <a href="{{ route('gyms.edit', $gym) }}" class="btn btn-primary">
                            <i class="bi bi-pencil-square me-1"></i>Edit Profile
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-start border-primary border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Total Members</div>
                            <h3 class="mb-0">{{ $stats['totalMembers'] }}</h3>
                        </div>
                        <div class="text-primary" style="font-size: 2.5rem;">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-start border-success border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Active Subscriptions</div>
                            <h3 class="mb-0">{{ $stats['activeSubscriptions'] }}</h3>
                        </div>
                        <div class="text-success" style="font-size: 2.5rem;">
                            <i class="bi bi-calendar-check-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-start border-info border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Trainers</div>
                            <h3 class="mb-0">{{ $stats['trainers'] }}</h3>
                        </div>
                        <div class="text-info" style="font-size: 2.5rem;">
                            <i class="bi bi-person-workspace"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-start border-warning border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Total Classes</div>
                            <h3 class="mb-0">{{ $stats['classes'] }}</h3>
                        </div>
                        <div class="text-warning" style="font-size: 2.5rem;">
                            <i class="bi bi-collection-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-start border-danger border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Today's Check-ins</div>
                            <h3 class="mb-0">{{ $stats['todayCheckIns'] }}</h3>
                        </div>
                        <div class="text-danger" style="font-size: 2.5rem;">
                            <i class="bi bi-door-open-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-start border-secondary border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Monthly Revenue</div>
                            <h3 class="mb-0">${{ number_format($stats['monthlyRevenue'], 2) }}</h3>
                        </div>
                        <div class="text-secondary" style="font-size: 2.5rem;">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Details Tabs -->
    <div class="card shadow-sm">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#details">
                        <i class="bi bi-info-circle me-1"></i>Details
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#hours">
                        <i class="bi bi-clock me-1"></i>Opening Hours
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#staff">
                        <i class="bi bi-people me-1"></i>Staff
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <!-- Details Tab -->
                <div class="tab-pane fade show active" id="details">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th style="width: 200px;">Gym Name</th>
                                <td>{{ $gym->name }}</td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td>{{ $gym->address ?? 'Not specified' }}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{ $gym->phone ?? 'Not specified' }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $gym->email ?? 'Not specified' }}</td>
                            </tr>
                            <tr>
                                <th>Website</th>
                                <td>
                                    @if($gym->website)
                                        <a href="{{ $gym->website }}" target="_blank">{{ $gym->website }}</a>
                                    @else
                                        Not specified
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Timezone</th>
                                <td>{{ $gym->timezone ?? config('app.timezone') }}</td>
                            </tr>
                            <tr>
                                <th>Description</th>
                                <td>{{ $gym->description ?? 'No description available' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Opening Hours Tab -->
                <div class="tab-pane fade" id="hours">
                    @if($gym->opening_hours)
                        <div class="p-3">
                            {!! nl2br(e($gym->opening_hours)) !!}
                        </div>
                    @else
                        <div class="text-center text-muted p-5">
                            <i class="bi bi-clock" style="font-size: 3rem;"></i>
                            <p class="mt-3">Opening hours not specified</p>
                        </div>
                    @endif
                </div>

                <!-- Staff Tab -->
                <div class="tab-pane fade" id="staff">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($gym->users as $user)
                                    <tr>
                                        <td>
                                            @if($user->profile_photo)
                                                <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->name }}" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                                            @endif
                                            {{ $user->name }}
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <span class="badge bg-{{ $user->pivot->role === 'admin' ? 'danger' : ($user->pivot->role === 'manager' ? 'primary' : 'secondary') }}">
                                                {{ ucfirst($user->pivot->role) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No staff assigned to this gym</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('gyms.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back to Gyms
        </a>
    </div>
</div>
@endsection
