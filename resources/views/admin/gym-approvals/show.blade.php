@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mb-4">
        <a href="{{ route('admin.gym-approvals.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Approvals
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0"><i class="bi bi-building me-2"></i>{{ $gym->name }}</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>Gym Information</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th width="150">Slug:</th>
                            <td><code>{{ $gym->slug }}</code></td>
                        </tr>
                        <tr>
                            <th>Address:</th>
                            <td>{{ $gym->address ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td>{{ $gym->phone ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>
                                {{ $gym->email ?? 'N/A' }}
                                @if($gym->hasVerifiedEmail())
                                    <span class="badge bg-success ms-2">
                                        <i class="bi bi-patch-check-fill"></i> Verified
                                    </span>
                                @else
                                    <span class="badge bg-warning ms-2">
                                        <i class="bi bi-exclamation-triangle"></i> Not Verified
                                    </span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Subscription:</th>
                            <td>
                                <span class="badge bg-info">{{ ucfirst($gym->subscription_tier ?? 'trial') }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Trial Ends:</th>
                            <td>
                                @if($gym->trial_ends_at)
                                    {{ $gym->trial_ends_at->format('F d, Y') }}
                                    <br><small class="text-muted">{{ $gym->trial_ends_at->diffForHumans() }}</small>
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Registered:</th>
                            <td>{{ $gym->created_at->format('F d, Y H:i') }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <h5>Owner Information</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th width="150">Name:</th>
                            <td>{{ $gym->owner->name }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $gym->owner->email }}</td>
                        </tr>
                        <tr>
                            <th>Joined:</th>
                            <td>{{ $gym->owner->created_at->format('F d, Y') }}</td>
                        </tr>
                    </table>

                    <h5 class="mt-4">Approval Status</h5>
                    <div class="mb-3">
                        @if($gym->isPending())
                            <span class="badge bg-warning text-dark fs-6">
                                <i class="bi bi-clock-history"></i> Pending Approval
                            </span>
                        @elseif($gym->isApproved())
                            <span class="badge bg-success fs-6">
                                <i class="bi bi-check-circle"></i> Approved
                            </span>
                        @elseif($gym->isRejected())
                            <span class="badge bg-danger fs-6">
                                <i class="bi bi-x-circle"></i> Rejected
                            </span>
                        @endif
                    </div>

                    @if($gym->approved_at)
                        <table class="table table-borderless">
                            <tr>
                                <th width="150">{{ $gym->isApproved() ? 'Approved' : 'Rejected' }} By:</th>
                                <td>{{ $gym->approver?->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Date:</th>
                                <td>{{ $gym->approved_at->format('F d, Y H:i') }}</td>
                            </tr>
                        </table>
                    @endif

                    @if($gym->rejection_reason)
                        <div class="alert alert-danger">
                            <strong>Rejection Reason:</strong><br>
                            {{ $gym->rejection_reason }}
                        </div>
                    @endif
                </div>
            </div>

            @if($gym->isPending())
                <hr>
                <div class="d-flex gap-2 justify-content-end">
                    <form method="POST" action="{{ route('admin.gym-approvals.approve', $gym) }}">
                        @csrf
                        <button type="submit" class="btn btn-success" onclick="return confirm('Approve this gym?')">
                            <i class="bi bi-check-lg me-2"></i>Approve Gym
                        </button>
                    </form>

                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                        <i class="bi bi-x-lg me-2"></i>Reject Gym
                    </button>
                </div>

                <!-- Reject Modal -->
                <div class="modal fade" id="rejectModal" tabindex="-1">
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
                                        <label for="rejection_reason" class="form-label">Reason for Rejection*</label>
                                        <textarea class="form-control @error('rejection_reason') is-invalid @enderror" 
                                            name="rejection_reason" id="rejection_reason" rows="4" required>{{ old('rejection_reason') }}</textarea>
                                        @error('rejection_reason')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
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
            @endif
        </div>
    </div>
</div>
@endsection
