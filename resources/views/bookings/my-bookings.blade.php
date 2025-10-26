@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">My Bookings</h1>
            <p class="text-muted mb-0">View and manage your class bookings</p>
        </div>
        <a href="{{ route('bookings.index') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Book New Class
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Upcoming Bookings -->
    <div class="mb-5">
        <h4 class="mb-3"><i class="bi bi-calendar-event"></i> Upcoming Classes</h4>
        @forelse($upcomingBookings as $booking)
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="card-title mb-2">{{ $booking->gymClass->class_name }}</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1">
                                    <i class="bi bi-person-badge text-primary"></i>
                                    <strong>Trainer:</strong> {{ $booking->gymClass->trainer->name ?? 'TBA' }}
                                </p>
                                <p class="mb-1">
                                    <i class="bi bi-calendar-event text-primary"></i>
                                    <strong>Date:</strong> {{ $booking->gymClass->scheduled_at->format('M d, Y') }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1">
                                    <i class="bi bi-clock text-primary"></i>
                                    <strong>Time:</strong> {{ $booking->gymClass->scheduled_at->format('h:i A') }}
                                </p>
                                <p class="mb-1">
                                    <i class="bi bi-geo-alt text-primary"></i>
                                    <strong>Location:</strong> {{ $booking->gymClass->location ?? 'Main Studio' }}
                                </p>
                            </div>
                        </div>
                        <span class="badge bg-success">{{ ucfirst($booking->status) }}</span>
                        <small class="text-muted">Booked: {{ $booking->booked_at->format('M d, Y h:i A') }}</small>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="{{ route('bookings.show', $booking) }}" class="btn btn-outline-primary mb-2">
                            <i class="bi bi-eye"></i> View Details
                        </a>
                        @if($booking->canBeCancelled())
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelModal{{ $booking->id }}">
                            <i class="bi bi-x-circle"></i> Cancel Booking
                        </button>

                        <!-- Cancel Modal -->
                        <div class="modal fade" id="cancelModal{{ $booking->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Cancel Booking</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="{{ route('bookings.cancel', $booking) }}">
                                        @csrf
                                        <div class="modal-body">
                                            <p>Are you sure you want to cancel this booking?</p>
                                            <p><strong>Class:</strong> {{ $booking->gymClass->class_name }}</p>
                                            <p><strong>Date:</strong> {{ $booking->gymClass->scheduled_at->format('M d, Y h:i A') }}</p>
                                            
                                            <div class="mb-3">
                                                <label for="reason" class="form-label">Reason (Optional)</label>
                                                <textarea name="reason" id="reason" class="form-control" rows="3" placeholder="Why are you cancelling?"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keep Booking</button>
                                            <button type="submit" class="btn btn-danger">Yes, Cancel Booking</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> You don't have any upcoming bookings.
            <a href="{{ route('bookings.index') }}" class="alert-link">Browse available classes</a>
        </div>
        @endforelse
    </div>

    <!-- Past Bookings -->
    <div>
        <h4 class="mb-3"><i class="bi bi-clock-history"></i> Past Bookings</h4>
        @forelse($pastBookings as $booking)
        <div class="card mb-3 shadow-sm opacity-75">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-10">
                        <h5 class="card-title mb-2">{{ $booking->gymClass->class_name }}</h5>
                        <p class="mb-1">
                            <i class="bi bi-calendar-event text-muted"></i>
                            {{ $booking->gymClass->scheduled_at->format('M d, Y h:i A') }}
                            <span class="mx-2">|</span>
                            <i class="bi bi-person-badge text-muted"></i>
                            {{ $booking->gymClass->trainer->name ?? 'TBA' }}
                        </p>
                        <span class="badge {{ $booking->status === 'attended' ? 'bg-success' : ($booking->status === 'cancelled' ? 'bg-danger' : 'bg-secondary') }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>
                    <div class="col-md-2 text-end">
                        <a href="{{ route('bookings.show', $booking) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-eye"></i> View
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="alert alert-secondary">
            <i class="bi bi-inbox"></i> No past bookings found.
        </div>
        @endforelse
    </div>
</div>
@endsection
