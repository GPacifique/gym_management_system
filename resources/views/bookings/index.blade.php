@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Book a Class</h1>
            <p class="text-muted mb-0">Browse and book available classes</p>
        </div>
        <a href="{{ route('bookings.my-bookings') }}" class="btn btn-outline-primary">
            <i class="bi bi-calendar-check"></i> My Bookings
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        @forelse($classes as $class)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 sophisticated-booking-card" style="border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: all 0.3s ease;">
                <div class="card-header border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 1.25rem;">
                    <div class="d-flex justify-content-between align-items-start">
                        <h5 class="card-title mb-0 text-white fw-bold">{{ $class->class_name }}</h5>
                        @if($class->canBeBooked())
                            <span class="badge bg-success bg-gradient shadow-sm">
                                <i class="bi bi-check-circle me-1"></i>Available
                            </span>
                        @else
                            <span class="badge bg-danger bg-gradient shadow-sm">
                                <i class="bi bi-x-circle me-1"></i>Full
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="card-body" style="padding: 1.5rem;">
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-3 p-2 rounded" style="background-color: #f8f9fa;">
                            <i class="bi bi-person-badge text-primary fs-5 me-3"></i>
                            <div>
                                <small class="text-muted d-block" style="font-size: 0.75rem;">Trainer</small>
                                <strong>{{ $class->trainer->name ?? 'TBA' }}</strong>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center mb-3 p-2 rounded" style="background-color: #f8f9fa;">
                            <i class="bi bi-calendar-event text-success fs-5 me-3"></i>
                            <div>
                                <small class="text-muted d-block" style="font-size: 0.75rem;">Date & Time</small>
                                <strong>{{ $class->scheduled_at->format('M d, Y') }}</strong>
                                <span class="text-muted ms-2">{{ $class->scheduled_at->format('h:i A') }}</span>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center mb-3 p-2 rounded" style="background-color: #f8f9fa;">
                            <i class="bi bi-geo-alt text-danger fs-5 me-3"></i>
                            <div>
                                <small class="text-muted d-block" style="font-size: 0.75rem;">Location</small>
                                <strong>{{ $class->location ?? 'Main Studio' }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted fw-semibold">Capacity</small>
                            @php
                                $bookedCount = $class->confirmedBookings()->count();
                                $percentage = ($bookedCount / $class->capacity) * 100;
                            @endphp
                            <small class="fw-bold">{{ $bookedCount }}/{{ $class->capacity }}</small>
                        </div>
                        <div class="progress" style="height: 28px; border-radius: 8px;">
                            <div class="progress-bar {{ $percentage >= 90 ? 'bg-danger' : ($percentage >= 70 ? 'bg-warning' : 'bg-success') }} bg-gradient" 
                                 role="progressbar" 
                                 style="width: {{ $percentage }}%">
                                <span class="fw-semibold">{{ number_format($percentage, 0) }}%</span>
                            </div>
                        </div>
                        <small class="text-muted mt-1 d-block">
                            <i class="bi bi-info-circle me-1"></i>{{ $class->available_spots }} spots remaining
                        </small>
                    </div>

                    @if($class->canBeBooked())
                        <a href="{{ route('bookings.create', $class) }}" class="btn btn-primary bg-gradient w-100 py-2 fw-semibold" style="border-radius: 8px;">
                            <i class="bi bi-calendar-plus me-1"></i> Book This Class
                        </a>
                    @else
                        <button class="btn btn-secondary w-100 py-2" style="border-radius: 8px;" disabled>
                            <i class="bi bi-x-circle me-1"></i> Class Full
                        </button>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body text-center py-5">
                    <i class="bi bi-calendar-x" style="font-size: 4rem; opacity: 0.3; color: #6c757d;"></i>
                    <p class="mt-3 mb-2 fs-5 text-muted">No upcoming classes available</p>
                    <p class="text-muted small mb-0">Check back later for new class schedules</p>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <style>
        .sophisticated-booking-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15) !important;
        }
    </style>

    <div class="mt-4">
        {{ $classes->links() }}
    </div>
</div>
@endsection
