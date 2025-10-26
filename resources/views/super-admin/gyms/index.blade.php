@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">
                    <i class="bi bi-building"></i> Gym Account Management
                </h1>
                <a href="{{ route('super-admin.gyms.export', request()->query()) }}" class="btn btn-success">
                    <i class="bi bi-download"></i> Export to CSV
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Gyms</h6>
                            <h3 class="mb-0">{{ $stats['total'] }}</h3>
                        </div>
                        <div class="text-primary">
                            <i class="bi bi-building fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Approved</h6>
                            <h3 class="mb-0">{{ $stats['approved'] }}</h3>
                        </div>
                        <div class="text-success">
                            <i class="bi bi-check-circle fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Pending</h6>
                            <h3 class="mb-0">{{ $stats['pending'] }}</h3>
                        </div>
                        <div class="text-warning">
                            <i class="bi bi-clock-history fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">On Trial</h6>
                            <h3 class="mb-0">{{ $stats['on_trial'] }}</h3>
                        </div>
                        <div class="text-info">
                            <i class="bi bi-hourglass-split fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('super-admin.gyms.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Name, email, or phone..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Subscription Tier</label>
                        <select name="tier" class="form-select">
                            <option value="">All Tiers</option>
                            <option value="trial" {{ request('tier') == 'trial' ? 'selected' : '' }}>Trial</option>
                            <option value="basic" {{ request('tier') == 'basic' ? 'selected' : '' }}>Basic</option>
                            <option value="premium" {{ request('tier') == 'premium' ? 'selected' : '' }}>Premium</option>
                            <option value="enterprise" {{ request('tier') == 'enterprise' ? 'selected' : '' }}>Enterprise</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Gyms Table -->
    <div class="card">
        <div class="card-body">
            @if($gyms->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Logo</th>
                                <th>Gym Name</th>
                                <th>Owner</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th>Subscription</th>
                                <th>Members</th>
                                <th>Trial Ends</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($gyms as $gym)
                                <tr>
                                    <td>
                                        @if($gym->logo)
                                            <img src="{{ asset('storage/' . $gym->logo) }}" alt="{{ $gym->name }}" class="rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="bi bi-building text-white"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $gym->name }}</strong><br>
                                        <small class="text-muted">{{ $gym->email }}</small>
                                    </td>
                                    <td>
                                        @if($gym->owner)
                                            {{ $gym->owner->name }}<br>
                                            <small class="text-muted">{{ $gym->owner->email }}</small>
                                        @else
                                            <span class="text-muted">No owner</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $gym->phone }}<br>
                                        <small class="text-muted">{{ Str::limit($gym->address, 30) }}</small>
                                    </td>
                                    <td>
                                        @if($gym->approval_status === 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($gym->approval_status === 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($gym->approval_status === 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @elseif($gym->approval_status === 'suspended')
                                            <span class="badge bg-dark">Suspended</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($gym->subscription_tier === 'trial')
                                            <span class="badge bg-info">Trial</span>
                                        @elseif($gym->subscription_tier === 'basic')
                                            <span class="badge bg-primary">Basic</span>
                                        @elseif($gym->subscription_tier === 'premium')
                                            <span class="badge bg-success">Premium</span>
                                        @elseif($gym->subscription_tier === 'enterprise')
                                            <span class="badge bg-danger">Enterprise</span>
                                        @endif
                                    </td>
                                    <td>
                                        <i class="bi bi-people"></i> {{ $gym->members->count() }}
                                    </td>
                                    <td>
                                        @if($gym->trial_ends_at)
                                            {{ $gym->trial_ends_at->format('M d, Y') }}
                                            @if($gym->trial_ends_at->isPast())
                                                <br><small class="text-danger">Expired</small>
                                            @elseif($gym->trial_ends_at->diffInDays() <= 7)
                                                <br><small class="text-warning">Expiring soon</small>
                                            @endif
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('super-admin.gyms.show', $gym) }}" class="btn btn-outline-primary" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            
                                            @if($gym->approval_status === 'pending')
                                                <form action="{{ route('super-admin.gyms.approve', $gym) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-outline-success" title="Approve" onclick="return confirm('Approve this gym?')">
                                                        <i class="bi bi-check-lg"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $gym->id }}" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>

                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteModal{{ $gym->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Delete Gym</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to delete <strong>{{ $gym->name }}</strong>?</p>
                                                        <p class="text-danger"><i class="bi bi-exclamation-triangle"></i> This will delete all members, payments, and related data. This action cannot be undone!</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <form action="{{ route('super-admin.gyms.destroy', $gym) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Delete Permanently</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $gyms->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-building fs-1 text-muted"></i>
                    <p class="text-muted mt-2">No gyms found.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
