@extends('layouts.app')
@section('title', 'View Member')

@section('content')
<div class="container mt-4">
    <!-- Header Card -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 2rem;">
            <div class="d-flex align-items-center">
                <div class="position-relative me-4">
                    <img src="{{ $member->photo_url }}" class="rounded-circle shadow" alt="{{ $member->name }}" style="width: 100px; height: 100px; object-fit: cover; border: 4px solid white;">
                    <span class="position-absolute bottom-0 end-0 badge bg-success rounded-circle" style="width: 25px; height: 25px; padding: 0;">
                        <i class="bi bi-check-lg"></i>
                    </span>
                </div>
                <div class="text-white">
                    <h3 class="mb-1 fw-bold">{{ $member->name ?? ($member->first_name . ' ' . $member->last_name) }}</h3>
                    <p class="mb-0 opacity-75">
                        <i class="bi bi-person-badge me-2"></i>Member #{{ $member->id }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Personal Information -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-header bg-white border-0 pb-0" style="padding: 1.5rem 1.5rem 1rem;">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-person-circle text-primary me-2"></i>Personal Information
                    </h5>
                </div>
                <div class="card-body" style="padding: 1.5rem;">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="p-3 rounded" style="background-color: #f8f9fa;">
                                <small class="text-muted d-block mb-1">Email Address</small>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-envelope text-primary me-2 fs-5"></i>
                                    <span class="fw-semibold">{{ $member->email }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-3 rounded" style="background-color: #f8f9fa;">
                                <small class="text-muted d-block mb-1">Phone Number</small>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-telephone text-success me-2 fs-5"></i>
                                    <span class="fw-semibold">{{ $member->phone ?: 'Not provided' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded" style="background-color: #f8f9fa;">
                                <small class="text-muted d-block mb-1">Gender</small>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-gender-ambiguous text-info me-2"></i>
                                    <span class="fw-semibold">{{ ucfirst($member->gender) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded" style="background-color: #f8f9fa;">
                                <small class="text-muted d-block mb-1">Date of Birth</small>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-calendar-event text-warning me-2"></i>
                                    <span class="fw-semibold">{{ \Carbon\Carbon::parse($member->dob)->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-3 rounded" style="background-color: #f8f9fa;">
                                <small class="text-muted d-block mb-1">Address</small>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-geo-alt text-danger me-2 fs-5"></i>
                                    <span class="fw-semibold">{{ $member->address ?: 'Not provided' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Membership Information -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-header bg-white border-0 pb-0" style="padding: 1.5rem 1.5rem 1rem;">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-card-checklist text-success me-2"></i>Membership Details
                    </h5>
                </div>
                <div class="card-body" style="padding: 1.5rem;">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="p-3 rounded" style="background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);">
                                <small class="text-muted d-block mb-1">Assigned Trainer</small>
                                <div class="d-flex align-items-center">
                                    @if($member->trainer)
                                        @php
                                            $trainerName = $member->trainer->name ?? '';
                                            $initials = '';
                                            if ($trainerName !== '') {
                                                $parts = preg_split('/\s+/', trim($trainerName));
                                                if (!empty($parts[0])) { $initials .= mb_strtoupper(mb_substr($parts[0], 0, 1)); }
                                                if (!empty($parts[1])) { $initials .= mb_strtoupper(mb_substr($parts[1], 0, 1)); }
                                            }
                                        @endphp
                                        <div class="avatar-circle me-2" style="width: 36px; height: 36px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 50%; display: flex; align-items-center; justify-content: center; color: white; font-weight: 600; font-size: 0.8rem;">
                                            {{ $initials !== '' ? $initials : '?' }}
                                        </div>
                                        <span class="fw-bold">{{ $member->trainer->name }}</span>
                                    @else
                                        <i class="bi bi-person-x text-secondary me-2"></i>
                                        <span class="text-muted">Unassigned</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-3 rounded" style="background: linear-gradient(135deg, #f093fb15 0%, #f5576c15 100%);">
                                <small class="text-muted d-block mb-1">Join Date</small>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-calendar-plus text-primary me-2 fs-5"></i>
                                    <div>
                                        <span class="fw-semibold d-block">{{ \Carbon\Carbon::parse($member->join_date)->format('M d, Y') }}</span>
                                        <small class="text-muted">({{ \Carbon\Carbon::parse($member->join_date)->diffForHumans() }})</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-3 rounded text-center" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                                <small class="text-muted d-block mb-2">Member Since</small>
                                @php
                                    $daysSinceJoined = (int) \Carbon\Carbon::parse($member->join_date)->diffInDays(now());
                                @endphp
                                <h2 class="mb-0 fw-bold text-primary" style="font-size: 3rem;">{{ number_format($daysSinceJoined) }}</h2>
                                <small class="text-muted fw-semibold">{{ $daysSinceJoined === 1 ? 'Day' : 'Days' }} as Member</small>
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
            <a href="{{ route('members.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Members
            </a>
            <div class="d-flex gap-2">
                <form action="{{ route('attendances.store') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="member_id" value="{{ $member->id }}">
                    <button type="submit" class="btn btn-primary bg-gradient" title="Record Attendance"
                        onclick="return confirm('Record check-in for {{ $member->first_name }} {{ $member->last_name }}?')">
                        <i class="bi bi-check2-circle me-1"></i> Record Attendance
                    </button>
                </form>
                <a href="{{ route('members.edit', $member) }}" class="btn btn-warning">
                    <i class="bi bi-pencil me-1"></i> Edit Member
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
