@extends('layouts.app')
@section('title', 'Trainer Details')

@section('content')
<div class="container mt-4">
    <!-- Header Card -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header border-0" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); padding: 2rem;">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    @php
                        $trainerName = $trainer->name ?? '';
                        $initials = '';
                        if ($trainerName !== '') {
                            $parts = preg_split('/\s+/', trim($trainerName));
                            if (!empty($parts[0])) { $initials .= mb_strtoupper(mb_substr($parts[0], 0, 1)); }
                            if (!empty($parts[1])) { $initials .= mb_strtoupper(mb_substr($parts[1], 0, 1)); }
                        }
                    @endphp
                    <div class="rounded-circle shadow me-4" style="width: 100px; height: 100px; background: white; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; font-weight: 700; color: #4facfe;">
                        {{ $initials !== '' ? $initials : '?' }}
                    </div>
                    <div class="text-white">
                        <h3 class="mb-1 fw-bold">{{ $trainer->name }}</h3>
                        <p class="mb-0 opacity-75">
                            <i class="bi bi-person-badge me-2"></i>Professional Trainer
                        </p>
                    </div>
                </div>
                @if($trainer->specialization)
                    <span class="badge bg-white text-dark shadow" style="font-size: 1rem; padding: 0.5rem 1rem;">
                        <i class="bi bi-award me-1"></i>{{ $trainer->specialization }}
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Contact Information -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-header bg-white border-0 pb-0" style="padding: 1.5rem 1.5rem 1rem;">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-telephone-inbound text-primary me-2"></i>Contact Information
                    </h5>
                </div>
                <div class="card-body" style="padding: 1.5rem;">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="p-3 rounded" style="background-color: #f8f9fa;">
                                <small class="text-muted d-block mb-1">Email Address</small>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-envelope text-primary me-2 fs-5"></i>
                                    <span class="fw-semibold">{{ $trainer->email }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-3 rounded" style="background-color: #f8f9fa;">
                                <small class="text-muted d-block mb-1">Phone Number</small>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-telephone text-success me-2 fs-5"></i>
                                    <span class="fw-semibold">{{ $trainer->phone }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Professional Information -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-header bg-white border-0 pb-0" style="padding: 1.5rem 1.5rem 1rem;">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-briefcase text-success me-2"></i>Professional Details
                    </h5>
                </div>
                <div class="card-body" style="padding: 1.5rem;">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="p-3 rounded" style="background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);">
                                <small class="text-muted d-block mb-1">Specialization</small>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-star-fill text-warning me-2 fs-5"></i>
                                    <span class="fw-bold">{{ $trainer->specialization ?? 'Not specified' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-3 rounded" style="background: linear-gradient(135deg, #f093fb15 0%, #f5576c15 100%);">
                                <small class="text-muted d-block mb-1">Monthly Salary</small>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-currency  text-success me-2 fs-5"></i>
                                        <span class="fw-bold fs-4 text-success">RWF {{ number_format($trainer->salary ?? 0, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-3 rounded" style="background-color: #f8f9fa;">
                                <small class="text-muted d-block mb-1">Joined Date</small>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-calendar-check text-primary me-2 fs-5"></i>
                                    <span class="fw-semibold">{{ $trainer->created_at->format('M d, Y') }}</span>
                                    <span class="ms-2 text-muted small">({{ $trainer->created_at->diffForHumans() }})</span>
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
            <a href="{{ route('trainers.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Trainers
            </a>
            <a href="{{ route('trainers.edit', $trainer) }}" class="btn btn-warning">
                <i class="bi bi-pencil me-1"></i> Edit Trainer
            </a>
        </div>
    </div>
</div>
@endsection
