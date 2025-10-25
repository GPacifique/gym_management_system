@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-shield-check me-2"></i>Gym Approvals</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-4" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#pending">
                <i class="bi bi-clock-history me-2"></i>Pending
                @if($pendingGyms->total() > 0)
                    <span class="badge bg-warning text-dark">{{ $pendingGyms->total() }}</span>
                @endif
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#approved">
                <i class="bi bi-check-circle me-2"></i>Approved
                @if($approvedGyms->total() > 0)
                    <span class="badge bg-success">{{ $approvedGyms->total() }}</span>
                @endif
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#rejected">
                <i class="bi bi-x-circle me-2"></i>Rejected
                @if($rejectedGyms->total() > 0)
                    <span class="badge bg-danger">{{ $rejectedGyms->total() }}</span>
                @endif
            </a>
        </li>
    </ul>

    <!-- Tab content -->
    <div class="tab-content">
        <!-- Pending Gyms -->
        <div class="tab-pane fade show active" id="pending">
            @if($pendingGyms->isEmpty())
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>No pending gym registrations.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Gym Name</th>
                                <th>Owner</th>
                                <th>Email</th>
                                <th>Registered</th>
                                <th>Trial Ends</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingGyms as $gym)
                            <tr>
                                <td>
                                    <strong>{{ $gym->name }}</strong><br>
                                    <small class="text-muted">{{ $gym->slug }}</small>
                                </td>
                                <td>{{ $gym->owner->name }}</td>
                                <td>
                                    {{ $gym->owner->email }}
                                    @if($gym->hasVerifiedEmail())
                                        <i class="bi bi-patch-check-fill text-success" title="Email verified"></i>
                                    @else
                                        <i class="bi bi-exclamation-triangle text-warning" title="Email not verified"></i>
                                    @endif
                                </td>
                                <td>{{ $gym->created_at->diffForHumans() }}</td>
                                <td>
                                    @if($gym->trial_ends_at)
                                        {{ $gym->trial_ends_at->format('M d, Y') }}
                                        <br><small class="text-muted">{{ $gym->trial_ends_at->diffForHumans() }}</small>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.gym-approvals.show', $gym) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <form method="POST" action="{{ route('admin.gym-approvals.approve', $gym) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Approve this gym?')">
                                            <i class="bi bi-check-lg"></i> Approve
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $gym->id }}">
                                        <i class="bi bi-x-lg"></i> Reject
                                    </button>

                                    <!-- Reject Modal -->
                                    <div class="modal fade" id="rejectModal{{ $gym->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ route('admin.gym-approvals.reject', $gym) }}">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Reject Gym Registration</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>You are about to reject <strong>{{ $gym->name }}</strong></p>
                                                        <div class="mb-3">
                                                            <label for="rejection_reason{{ $gym->id }}" class="form-label">Reason for Rejection*</label>
                                                            <textarea class="form-control" name="rejection_reason" id="rejection_reason{{ $gym->id }}" rows="4" required></textarea>
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
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $pendingGyms->links() }}
            @endif
        </div>

        <!-- Approved Gyms -->
        <div class="tab-pane fade" id="approved">
            @if($approvedGyms->isEmpty())
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>No approved gyms yet.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Gym Name</th>
                                <th>Owner</th>
                                <th>Approved By</th>
                                <th>Approved At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($approvedGyms as $gym)
                            <tr>
                                <td>
                                    <strong>{{ $gym->name }}</strong><br>
                                    <small class="text-muted">{{ $gym->slug }}</small>
                                </td>
                                <td>{{ $gym->owner->name }}</td>
                                <td>{{ $gym->approver?->name ?? 'N/A' }}</td>
                                <td>{{ $gym->approved_at?->format('M d, Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.gym-approvals.show', $gym) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $approvedGyms->links() }}
            @endif
        </div>

        <!-- Rejected Gyms -->
        <div class="tab-pane fade" id="rejected">
            @if($rejectedGyms->isEmpty())
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>No rejected gyms.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Gym Name</th>
                                <th>Owner</th>
                                <th>Rejected By</th>
                                <th>Reason</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rejectedGyms as $gym)
                            <tr>
                                <td>
                                    <strong>{{ $gym->name }}</strong><br>
                                    <small class="text-muted">{{ $gym->slug }}</small>
                                </td>
                                <td>{{ $gym->owner->name }}</td>
                                <td>{{ $gym->approver?->name ?? 'N/A' }}</td>
                                <td>
                                    <small>{{ Str::limit($gym->rejection_reason, 50) }}</small>
                                </td>
                                <td>
                                    <a href="{{ route('admin.gym-approvals.show', $gym) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $rejectedGyms->links() }}
            @endif
        </div>
    </div>
</div>
@endsection
