@extends('layouts.app')
@section('title', 'Subscription Details')

@section('content')
<div class="container mt-4">
    <!-- Header Card -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header border-0" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 2rem;">
            <div class="text-white text-center">
                <div class="mb-3">
                    <i class="bi bi-card-checklist" style="font-size: 3rem;"></i>
                </div>
                <h3 class="mb-1 fw-bold">{{ $subscription->plan_name }}</h3>
                <p class="mb-0 opacity-75">Subscription Details</p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Member & Status -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-header bg-white border-0 pb-0" style="padding: 1.5rem 1.5rem 1rem;">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-person-circle text-primary me-2"></i>Member Information
                    </h5>
                </div>
                <div class="card-body" style="padding: 1.5rem;">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="p-3 rounded" style="background-color: #f8f9fa;">
                                <small class="text-muted d-block mb-2">Subscriber</small>
                                @php
                                    $memberName = $subscription->member->full_name ?? 'N/A';
                                    $initials = '';
                                    if ($memberName !== 'N/A') {
                                        $parts = preg_split('/\s+/', trim($memberName));
                                        if (!empty($parts[0])) { $initials .= mb_strtoupper(mb_substr($parts[0], 0, 1)); }
                                        if (!empty($parts[1])) { $initials .= mb_strtoupper(mb_substr($parts[1], 0, 1)); }
                                    }
                                @endphp
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-3" style="width: 42px; height: 42px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 50%; display: flex; align-items-center; justify-content: center; color: white; font-weight: 600; font-size: 0.9rem;">
                                        {{ $initials !== '' ? $initials : '?' }}
                                    </div>
                                    <span class="fw-bold">{{ $memberName }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="p-3 rounded text-center" style="background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);">
                                <small class="text-muted d-block mb-2">Subscription Status</small>
                                @if($subscription->status == 'active')
                                    <span class="badge bg-success bg-gradient" style="font-size: 1.1rem; padding: 0.5rem 1.5rem;">
                                        <i class="bi bi-check-circle me-1"></i>Active
                                    </span>
                                @elseif($subscription->status == 'expired')
                                    <span class="badge bg-danger bg-gradient" style="font-size: 1.1rem; padding: 0.5rem 1.5rem;">
                                        <i class="bi bi-x-circle me-1"></i>Expired
                                    </span>
                                @else
                                    <span class="badge bg-secondary bg-gradient" style="font-size: 1.1rem; padding: 0.5rem 1.5rem;">
                                        {{ ucfirst($subscription->status) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Plan Details -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-header bg-white border-0 pb-0" style="padding: 1.5rem 1.5rem 1rem;">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-tag text-success me-2"></i>Plan Details
                    </h5>
                </div>
                <div class="card-body" style="padding: 1.5rem;">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="p-3 rounded text-center" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                                <small class="text-muted d-block mb-2">Subscription Price</small>
                                <h2 class="mb-0 fw-bold text-success">
                                    RWF {{ number_format($subscription->price, 2) }}
                                </h2>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="p-3 rounded" style="background-color: #f8f9fa;">
                                <small class="text-muted d-block mb-1">Start Date</small>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-calendar-check text-success me-2"></i>
                                    <span class="fw-semibold">{{ $subscription->start_date->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="p-3 rounded" style="background-color: #f8f9fa;">
                                <small class="text-muted d-block mb-1">End Date</small>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-calendar-x text-danger me-2"></i>
                                    <span class="fw-semibold">{{ $subscription->end_date->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="p-3 rounded" style="background: linear-gradient(135deg, #ffecd215 0%, #fcb69f15 100%);">
                                <small class="text-muted d-block mb-1">Duration</small>
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="bi bi-clock-history text-primary me-2 fs-5"></i>
                                    <span class="fw-bold fs-5">{{ $subscription->start_date->diffInDays($subscription->end_date) }} Days</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="card border-0 shadow-sm mt-4" style="border-radius: 12px;">
        <div class="card-body d-flex justify-content-between align-items-center" style="padding: 1.25rem 1.5rem;">
            <a href="{{ route('subscriptions.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Subscriptions
            </a>
            <a href="{{ route('subscriptions.edit', $subscription) }}" class="btn btn-warning">
                <i class="bi bi-pencil-square me-1"></i> Edit Subscription
            </a>
        </div>
    </div>
</div>
@endsection
