@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-calendar-plus"></i> Book Class</h4>
                </div>
                <div class="card-body">
                    <h5 class="mb-4">{{ $class->class_name }}</h5>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p><strong><i class="bi bi-person-badge"></i> Trainer:</strong><br>{{ $class->trainer->name ?? 'TBA' }}</p>
                            <p><strong><i class="bi bi-calendar-event"></i> Date:</strong><br>{{ $class->scheduled_at->format('l, M d, Y') }}</p>
                            <p><strong><i class="bi bi-clock"></i> Time:</strong><br>{{ $class->scheduled_at->format('h:i A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong><i class="bi bi-geo-alt"></i> Location:</strong><br>{{ $class->location ?? 'Main Studio' }}</p>
                            <p><strong><i class="bi bi-people"></i> Capacity:</strong><br>{{ $class->capacity }} people</p>
                            <p><strong><i class="bi bi-check-circle"></i> Available Spots:</strong><br>
                                <span class="badge {{ $class->available_spots > 5 ? 'bg-success' : 'bg-warning' }} fs-6">
                                    {{ $class->available_spots }} spots left
                                </span>
                            </p>
                        </div>
                    </div>

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('bookings.store', $class) }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="member_id" class="form-label">Select Member <span class="text-danger">*</span></label>
                            <select name="member_id" id="member_id" class="form-select @error('member_id') is-invalid @enderror" required>
                                <option value="">-- Choose Member --</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                                        {{ $member->name }} - {{ $member->email }}
                                    </option>
                                @endforeach
                            </select>
                            @error('member_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            <strong>Booking Policy:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Bookings can be cancelled up to 2 hours before class start time</li>
                                <li>Late cancellations may incur a fee</li>
                                <li>Please arrive 10 minutes early</li>
                            </ul>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="bi bi-check-lg"></i> Confirm Booking
                            </button>
                            <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-lg"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
