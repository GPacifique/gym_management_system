@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <a href="{{ route('super-admin.gyms.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
                        <i class="bi bi-arrow-left"></i> Back to List
                    </a>
                    <h1 class="h3 mb-0">{{ $gym->name }}</h1>
                </div>
                <div class="btn-group">
                    @if($gym->approval_status === 'pending')
                        <form action="{{ route('super-admin.gyms.approve', $gym) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success" onclick="return confirm('Approve this gym?')">
                                <i class="bi bi-check-lg"></i> Approve
                            </button>
                        </form>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="bi bi-x-lg"></i> Reject
                        </button>
                    @elseif($gym->approval_status === 'approved')
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#suspendModal">
                            <i class="bi bi-pause-circle"></i> Suspend
                        </button>
                    @endif
                    
                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#subscriptionModal">
                        <i class="bi bi-credit-card"></i> Manage Subscription
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Badge -->
    <div class="row mb-4">
        <div class="col-12">
            @if($gym->approval_status === 'approved')
                <span class="badge bg-success fs-6">
                    <i class="bi bi-check-circle"></i> Approved
                    @if($gym->approved_at)
                        on {{ $gym->approved_at->format('M d, Y') }}
                    @endif
                </span>
            @elseif($gym->approval_status === 'pending')
                <span class="badge bg-warning fs-6"><i class="bi bi-clock-history"></i> Pending Approval</span>
            @elseif($gym->approval_status === 'rejected')
                <span class="badge bg-danger fs-6"><i class="bi bi-x-circle"></i> Rejected</span>
            @elseif($gym->approval_status === 'suspended')
                <span class="badge bg-dark fs-6"><i class="bi bi-pause-circle"></i> Suspended</span>
            @endif

            @if($gym->isOnTrial())
                <span class="badge bg-info fs-6 ms-2">
                    <i class="bi bi-hourglass-split"></i> On Trial ({{ $gym->trial_ends_at->diffForHumans() }})
                </span>
            @elseif($gym->trialExpired())
                <span class="badge bg-danger fs-6 ms-2">
                    <i class="bi bi-exclamation-triangle"></i> Trial Expired
                </span>
            @endif

            @if($gym->rejection_reason)
                <div class="alert alert-danger mt-2">
                    <strong>Rejection/Suspension Reason:</strong> {{ $gym->rejection_reason }}
                </div>
            @endif
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-primary">
                <div class="card-body text-center">
                    <i class="bi bi-people fs-1 text-primary"></i>
                    <h3 class="mt-2">{{ $stats['total_members'] }}</h3>
                    <p class="text-muted mb-0">Total Members</p>
                    <small class="text-success">{{ $stats['active_members'] }} active</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-info">
                <div class="card-body text-center">
                    <i class="bi bi-person-badge fs-1 text-info"></i>
                    <h3 class="mt-2">{{ $stats['total_users'] }}</h3>
                    <p class="text-muted mb-0">Staff Members</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <i class="bi bi-cash-stack fs-1 text-warning"></i>
                    <h3 class="mt-2">{{ $stats['pending_payments'] }}</h3>
                    <p class="text-muted mb-0">Pending Payments</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body text-center">
                    <i class="bi bi-calendar-check fs-1 text-success"></i>
                    <h3 class="mt-2">{{ $gym->created_at->format('M d, Y') }}</h3>
                    <p class="text-muted mb-0">Registered</p>
                    <small class="text-muted">{{ $gym->created_at->diffForHumans() }}</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Gym Information -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Gym Information</h5>
                </div>
                <div class="card-body">
                    @if($gym->logo)
                        <div class="text-center mb-3">
                            <img src="{{ asset('storage/' . $gym->logo) }}" alt="{{ $gym->name }}" class="img-fluid rounded" style="max-height: 150px;">
                        </div>
                    @endif
                    
                    <table class="table table-sm">
                        <tr>
                            <th width="40%">Name:</th>
                            <td>{{ $gym->name }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>
                                {{ $gym->email }}
                                @if($gym->hasVerifiedEmail())
                                    <span class="badge bg-success">Verified</span>
                                @else
                                    <span class="badge bg-warning">Not Verified</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td>{{ $gym->phone }}</td>
                        </tr>
                        <tr>
                            <th>Address:</th>
                            <td>{{ $gym->address }}</td>
                        </tr>
                        <tr>
                            <th>Website:</th>
                            <td>
                                @if($gym->website)
                                    <a href="{{ $gym->website }}" target="_blank">{{ $gym->website }}</a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Timezone:</th>
                            <td>{{ $gym->timezone }}</td>
                        </tr>
                        <tr>
                            <th>Opening Hours:</th>
                            <td>{{ $gym->opening_hours ?? 'Not specified' }}</td>
                        </tr>
                        <tr>
                            <th>Subscription:</th>
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
                        </tr>
                    </table>

                    @if($gym->description)
                        <hr>
                        <h6>Description:</h6>
                        <p class="text-muted">{{ $gym->description }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Owner Information -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-person-circle"></i> Owner Information</h5>
                </div>
                <div class="card-body">
                    @if($gym->owner)
                        <table class="table table-sm">
                            <tr>
                                <th width="40%">Name:</th>
                                <td>{{ $gym->owner->name }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ $gym->owner->email }}</td>
                            </tr>
                            <tr>
                                <th>Role:</th>
                                <td><span class="badge bg-primary">{{ ucfirst($gym->owner->role) }}</span></td>
                            </tr>
                            <tr>
                                <th>Joined:</th>
                                <td>{{ $gym->owner->created_at->format('M d, Y') }}</td>
                            </tr>
                        </table>
                    @else
                        <p class="text-muted">No owner assigned</p>
                    @endif

                    @if($gym->approver)
                        <hr>
                        <h6>Approved By:</h6>
                        <p class="mb-1"><strong>{{ $gym->approver->name }}</strong></p>
                        <p class="text-muted mb-0">{{ $gym->approver->email }}</p>
                        <small class="text-muted">on {{ $gym->approved_at->format('M d, Y H:i') }}</small>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Staff Members -->
    @if($gym->assignedUsers->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-people"></i> Staff Members ({{ $gym->assignedUsers->count() }})</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Joined</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($gym->assignedUsers as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td><span class="badge bg-info">{{ ucfirst($user->role) }}</span></td>
                                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('super-admin.gyms.reject', $gym) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title">Reject Gym</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea name="rejection_reason" class="form-control" rows="4" required placeholder="Explain why this gym is being rejected..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Gym</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Suspend Modal -->
<div class="modal fade" id="suspendModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('super-admin.gyms.suspend', $gym) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title">Suspend Gym</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Suspension Reason <span class="text-danger">*</span></label>
                        <textarea name="suspension_reason" class="form-control" rows="4" required placeholder="Explain why this gym is being suspended..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Suspend Gym</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Subscription Modal -->
<div class="modal fade" id="subscriptionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('super-admin.gyms.update-subscription', $gym) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title">Manage Subscription</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Subscription Tier <span class="text-danger">*</span></label>
                        <select name="subscription_tier" class="form-select" required>
                            <option value="trial" {{ $gym->subscription_tier === 'trial' ? 'selected' : '' }}>Trial</option>
                            <option value="basic" {{ $gym->subscription_tier === 'basic' ? 'selected' : '' }}>Basic</option>
                            <option value="premium" {{ $gym->subscription_tier === 'premium' ? 'selected' : '' }}>Premium</option>
                            <option value="enterprise" {{ $gym->subscription_tier === 'enterprise' ? 'selected' : '' }}>Enterprise</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Trial End Date</label>
                        <input type="date" name="trial_ends_at" class="form-control" value="{{ $gym->trial_ends_at?->format('Y-m-d') }}">
                        <small class="text-muted">Only applicable for trial subscriptions</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Subscription</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
