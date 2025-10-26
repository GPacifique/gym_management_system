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
                <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                    <div class="card-body text-center py-4">
                        <i class="bi bi-clock-history" style="font-size: 3rem; opacity: 0.3; color: #6c757d;"></i>
                        <p class="mt-3 mb-0 text-muted">No pending gym registrations</p>
                    </div>
                </div>
            @else
                <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                    <x-sophisticated-table :headers="['Gym Information', 'Owner', 'Email Status', 'Registered', 'Trial Ends', 'Actions']">
                        @foreach($pendingGyms as $gym)
                        <tr>
                            <td>
                                <div class="fw-bold text-dark" style="font-size: 1.05rem;">{{ $gym->name }}</div>
                                <small class="text-muted"><i class="bi bi-link-45deg me-1"></i>{{ $gym->slug }}</small>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @php
                                        $ownerName = $gym->owner->name ?? '';
                                        $initials = '';
                                        if ($ownerName !== '') {
                                            $parts = preg_split('/\s+/', trim($ownerName));
                                            if (!empty($parts[0])) { $initials .= mb_strtoupper(mb_substr($parts[0], 0, 1)); }
                                            if (!empty($parts[1])) { $initials .= mb_strtoupper(mb_substr($parts[1], 0, 1)); }
                                        }
                                    @endphp
                                    <div class="avatar-circle me-2" style="width: 38px; height: 38px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items-center; justify-content: center; color: white; font-weight: 600; font-size: 0.85rem;">
                                        {{ $initials !== '' ? $initials : '?' }}
                                    </div>
                                    <span class="fw-semibold">{{ $gym->owner->name }}</span>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <small>{{ $gym->owner->email }}</small>
                                    <br>
                                    @if($gym->hasVerifiedEmail())
                                        <span class="badge bg-success bg-gradient">
                                            <i class="bi bi-patch-check-fill me-1"></i>Verified
                                        </span>
                                    @else
                                        <span class="badge bg-warning bg-gradient">
                                            <i class="bi bi-exclamation-triangle me-1"></i>Not Verified
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <i class="bi bi-calendar-plus text-primary me-1"></i>
                                <span>{{ $gym->created_at->diffForHumans() }}</span>
                            </td>
                            <td>
                                @if($gym->trial_ends_at)
                                    <div>
                                        <i class="bi bi-calendar-x text-danger me-1"></i>
                                        <span>{{ $gym->trial_ends_at->format('M d, Y') }}</span>
                                    </div>
                                    <small class="text-muted">{{ $gym->trial_ends_at->diffForHumans() }}</small>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.gym-approvals.show', $gym) }}" class="btn btn-outline-info" title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.gym-approvals.approve', $gym) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success" onclick="return confirm('Approve {{ $gym->name }}?')" title="Approve">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $gym->id }}" title="Reject">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>

                                <!-- Reject Modal -->
                                <div class="modal fade" id="rejectModal{{ $gym->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content" style="border-radius: 12px;">
                                            <form method="POST" action="{{ route('admin.gym-approvals.reject', $gym) }}">
                                                @csrf
                                                <div class="modal-header border-0 pb-0">
                                                    <h5 class="modal-title fw-bold">Reject Gym Registration</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="text-muted">You are about to reject <strong class="text-dark">{{ $gym->name }}</strong></p>
                                                    <div class="mb-3">
                                                        <label for="rejection_reason{{ $gym->id }}" class="form-label fw-semibold">Reason for Rejection*</label>
                                                        <textarea class="form-control" name="rejection_reason" id="rejection_reason{{ $gym->id }}" rows="4" required style="border-radius: 8px;"></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-0">
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
                    </x-sophisticated-table>
                    <div class="card-footer bg-white border-0 px-3">
                        {{ $pendingGyms->links() }}
                    </div>
                </div>
            @endif
        </div>

        <!-- Approved Gyms -->
        <div class="tab-pane fade" id="approved">
            @if($approvedGyms->isEmpty())
                <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                    <div class="card-body text-center py-4">
                        <i class="bi bi-check-circle" style="font-size: 3rem; opacity: 0.3; color: #198754;"></i>
                        <p class="mt-3 mb-0 text-muted">No approved gyms yet</p>
                    </div>
                </div>
            @else
                <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                    <x-sophisticated-table :headers="['Gym Information', 'Owner', 'Approved By', 'Approved At', 'Actions']">
                        @foreach($approvedGyms as $gym)
                        <tr>
                            <td>
                                <div class="fw-bold text-dark" style="font-size: 1.05rem;">{{ $gym->name }}</div>
                                <small class="text-muted"><i class="bi bi-link-45deg me-1"></i>{{ $gym->slug }}</small>
                            </td>
                            <td>
                                <span class="fw-semibold">{{ $gym->owner->name }}</span>
                            </td>
                            <td>
                                @if($gym->approver)
                                    <span class="badge bg-success bg-gradient">
                                        <i class="bi bi-person-check me-1"></i>{{ $gym->approver->name }}
                                    </span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($gym->approved_at)
                                    <div>
                                        <i class="bi bi-calendar-check text-success me-1"></i>
                                        <span>{{ $gym->approved_at->format('M d, Y') }}</span>
                                    </div>
                                    <small class="text-muted">{{ $gym->approved_at->format('H:i') }}</small>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.gym-approvals.show', $gym) }}" class="btn btn-sm btn-outline-info" title="View Details">
                                    <i class="bi bi-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </x-sophisticated-table>
                    <div class="card-footer bg-white border-0 px-3">
                        {{ $approvedGyms->links() }}
                    </div>
                </div>
            @endif
        </div>

        <!-- Rejected Gyms -->
        <div class="tab-pane fade" id="rejected">
            @if($rejectedGyms->isEmpty())
                <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                    <div class="card-body text-center py-4">
                        <i class="bi bi-x-circle" style="font-size: 3rem; opacity: 0.3; color: #dc3545;"></i>
                        <p class="mt-3 mb-0 text-muted">No rejected gyms</p>
                    </div>
                </div>
            @else
                <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                    <x-sophisticated-table :headers="['Gym Information', 'Owner', 'Rejected By', 'Rejection Reason', 'Actions']">
                        @foreach($rejectedGyms as $gym)
                        <tr class="table-light opacity-75">
                            <td>
                                <div class="fw-bold text-dark" style="font-size: 1.05rem;">{{ $gym->name }}</div>
                                <small class="text-muted"><i class="bi bi-link-45deg me-1"></i>{{ $gym->slug }}</small>
                            </td>
                            <td>
                                <span class="fw-semibold">{{ $gym->owner->name }}</span>
                            </td>
                            <td>
                                @if($gym->approver)
                                    <span class="badge bg-danger bg-gradient">
                                        <i class="bi bi-person-x me-1"></i>{{ $gym->approver->name }}
                                    </span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ Str::limit($gym->rejection_reason, 60) }}</small>
                            </td>
                            <td>
                                <a href="{{ route('admin.gym-approvals.show', $gym) }}" class="btn btn-sm btn-outline-info" title="View Details">
                                    <i class="bi bi-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </x-sophisticated-table>
                    <div class="card-footer bg-white border-0 px-3">
                        {{ $rejectedGyms->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
