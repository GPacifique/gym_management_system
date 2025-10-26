@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="bi bi-ticket-detailed"></i> Booking Details</h4>
                    <span class="badge {{ $booking->status === 'confirmed' ? 'bg-success' : ($booking->status === 'cancelled' ? 'bg-danger' : 'bg-secondary') }}">
                        {{ ucfirst($booking->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <!-- Class Information -->
                    <h5 class="border-bottom pb-2 mb-3"><i class="bi bi-calendar-event"></i> Class Information</h5>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p><strong>Class Name:</strong><br>{{ $booking->gymClass->class_name }}</p>
                            <p><strong>Trainer:</strong><br>{{ $booking->gymClass->trainer->name ?? 'TBA' }}</p>
                            <p><strong>Date:</strong><br>{{ $booking->gymClass->scheduled_at->format('l, M d, Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Time:</strong><br>{{ $booking->gymClass->scheduled_at->format('h:i A') }}</p>
                            <p><strong>Location:</strong><br>{{ $booking->gymClass->location ?? 'Main Studio' }}</p>
                            <p><strong>Capacity:</strong><br>{{ $booking->gymClass->capacity }} people</p>
                        </div>
                    </div>

                    <!-- Member Information -->
                    <h5 class="border-bottom pb-2 mb-3"><i class="bi bi-person"></i> Member Information</h5>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p><strong>Name:</strong><br>{{ $booking->member->name }}</p>
                            <p><strong>Email:</strong><br>{{ $booking->member->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Phone:</strong><br>{{ $booking->member->phone ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <!-- Booking Information -->
                    <h5 class="border-bottom pb-2 mb-3"><i class="bi bi-info-circle"></i> Booking Information</h5>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p><strong>Booking ID:</strong><br>#{{ $booking->id }}</p>
                            <p><strong>Booked At:</strong><br>{{ $booking->booked_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Status:</strong><br>
                                <span class="badge {{ $booking->status === 'confirmed' ? 'bg-success' : ($booking->status === 'cancelled' ? 'bg-danger' : 'bg-secondary') }} fs-6">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </p>
                            @if($booking->cancelled_at)
                            <p><strong>Cancelled At:</strong><br>{{ $booking->cancelled_at->format('M d, Y h:i A') }}</p>
                            @endif
                        </div>
                    </div>

                    @if($booking->cancellation_reason)
                    <div class="alert alert-warning">
                        <strong>Cancellation Reason:</strong>
                        <p class="mb-0">{{ $booking->cancellation_reason }}</p>
                    </div>
                    @endif

                    @if($booking->status === 'confirmed' && $booking->gymClass->scheduled_at->isFuture())
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <strong>Important Reminders:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Please arrive 10 minutes before class starts</li>
                            <li>Bring your own water bottle and towel</li>
                            <li>Wear appropriate workout attire</li>
                        </ul>
                    </div>
                    @endif

                    <!-- Actions -->
                    <div class="d-flex gap-2">
                        <a href="{{ route('bookings.my-bookings') }}" class="btn btn-outline-secondary flex-grow-1">
                            <i class="bi bi-arrow-left"></i> Back to My Bookings
                        </a>
                        @if($booking->canBeCancelled())
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                            <i class="bi bi-x-circle"></i> Cancel Booking
                        </button>

                        <!-- Cancel Modal -->
                        <div class="modal fade" id="cancelModal" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Cancel Booking</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="{{ route('bookings.cancel', $booking) }}">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="alert alert-warning">
                                                <i class="bi bi-exclamation-triangle"></i>
                                                Are you sure you want to cancel this booking?
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="reason" class="form-label">Reason (Optional)</label>
                                                <textarea name="reason" id="reason" class="form-control" rows="3" placeholder="Please let us know why you're cancelling"></textarea>
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
    </div>
</div>
@endsection
