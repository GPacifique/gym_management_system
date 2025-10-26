@extends('layouts.app')

@section('content')
<div class="container-fluid">
    {{-- Super Admin Header --}}
    <div class="alert alert-danger border-0 shadow-sm mb-4" role="alert">
        <div class="d-flex align-items-center">
            <i class="bi bi-shield-fill-check fs-3 me-3"></i>
            <div>
                <h5 class="alert-heading mb-1">Super Admin - Platform Manager</h5>
                <p class="mb-0 small">You have platform-wide access to all gym accounts and system settings.</p>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Super Admin Dashboard</h1>
    </div>

    <!-- Quick Stats -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 text-primary p-3 rounded">
                                <i class="bi bi-building fs-3"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Gyms</h6>
                            <h3 class="mb-0">{{ $stats['total_gyms'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 text-success p-3 rounded">
                                <i class="bi bi-people fs-3"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Members</h6>
                            <h3 class="mb-0">{{ $stats['total_members'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 text-warning p-3 rounded">
                                <i class="bi bi-clock-history fs-3"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Pending Approvals</h6>
                            <h3 class="mb-0">{{ $stats['pending_gyms'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 text-info p-3 rounded">
                                <i class="bi bi-currency-dollar fs-3"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Revenue</h6>
                            <h3 class="mb-0">${{ number_format($stats['total_revenue'] ?? 0, 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle d-inline-flex mb-3">
                        <i class="bi bi-building fs-1"></i>
                    </div>
                    <h5>Manage Gyms</h5>
                    <p class="text-muted">View, approve, and manage all gym accounts</p>
                    <a href="{{ route('super-admin.gyms.index') }}" class="btn btn-primary">
                        <i class="bi bi-arrow-right"></i> Go to Gyms
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-circle d-inline-flex mb-3">
                        <i class="bi bi-clock-history fs-1"></i>
                    </div>
                    <h5>Pending Approvals</h5>
                    <p class="text-muted">Review and approve gym registrations</p>
                    <a href="{{ route('super-admin.gyms.index', ['status' => 'pending']) }}" class="btn btn-warning">
                        <i class="bi bi-arrow-right"></i> View Pending
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="bg-success bg-opacity-10 text-success p-3 rounded-circle d-inline-flex mb-3">
                        <i class="bi bi-download fs-1"></i>
                    </div>
                    <h5>Export Data</h5>
                    <p class="text-muted">Download gym data for analysis</p>
                    <a href="{{ route('super-admin.gyms.export') }}" class="btn btn-success">
                        <i class="bi bi-download"></i> Export CSV
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Gyms -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Recent Gym Registrations</h5>
        </div>
        <div class="card-body">
            @if($recentGyms->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Gym Name</th>
                                <th>Owner</th>
                                <th>Contact</th>
                                <th>Registered</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentGyms as $gym)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($gym->logo)
                                                <img src="{{ Storage::url($gym->logo) }}" alt="{{ $gym->name }}" 
                                                     class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-secondary bg-opacity-10 rounded me-2 d-flex align-items-center justify-content-center"
                                                     style="width: 40px; height: 40px;">
                                                    <i class="bi bi-building text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-semibold">{{ $gym->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        {{ $gym->owner->name ?? 'N/A' }}
                                    </td>
                                    <td>
                                        <div>{{ $gym->email }}</div>
                                        <small class="text-muted">{{ $gym->phone }}</small>
                                    </td>
                                    <td>{{ $gym->created_at->format('M d, Y') }}</td>
                                    <td>
                                        @if($gym->approval_status === 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($gym->approval_status === 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($gym->approval_status === 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @else
                                            <span class="badge bg-secondary">Suspended</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('super-admin.gyms.show', $gym) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted text-center py-5">No recent gym registrations.</p>
            @endif
        </div>
    </div>
</div>
@endsection
