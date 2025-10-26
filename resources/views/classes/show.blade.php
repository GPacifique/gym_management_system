<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">
                <i class="bi bi-bookmark-star text-primary me-2"></i>Class Details
            </h2>
            <div class="d-flex gap-2">
                <a href="{{ route('classes.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
                <a href="{{ route('classes.edit', $class) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <form action="{{ route('classes.destroy', $class) }}" method="POST" onsubmit="return confirm('Delete this class?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <!-- Header Card -->
                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
                        <div class="card-header border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 2rem;">
                            <div class="d-flex justify-content-between align-items-center text-white">
                                <div>
                                    <h3 class="mb-1 fw-bold">{{ $class->class_name }}</h3>
                                    <p class="mb-0 opacity-75">Class #{{ $class->id }}</p>
                                </div>
                                @if($class->scheduled_at->isFuture())
                                    <span class="badge bg-info bg-gradient shadow" style="font-size: 1rem; padding: 0.5rem 1rem;">
                                        <i class="bi bi-clock-history me-1"></i>Upcoming
                                    </span>
                                @elseif($class->scheduled_at->isToday())
                                    <span class="badge bg-success bg-gradient shadow" style="font-size: 1rem; padding: 0.5rem 1rem;">
                                        <i class="bi bi-calendar-check me-1"></i>Today
                                    </span>
                                @else
                                    <span class="badge bg-secondary bg-gradient shadow" style="font-size: 1rem; padding: 0.5rem 1rem;">
                                        <i class="bi bi-calendar-x me-1"></i>Past
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row g-4">
                        <!-- Class Information -->
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                                <div class="card-header bg-white border-0 pb-0" style="padding: 1.5rem 1.5rem 1rem;">
                                    <h5 class="mb-0 fw-bold">
                                        <i class="bi bi-info-circle text-primary me-2"></i>Class Information
                                    </h5>
                                </div>
                                <div class="card-body" style="padding: 1.5rem;">
                                    <div class="row g-3">
                                        <!-- Trainer -->
                                        <div class="col-12">
                                            <div class="p-3 rounded" style="background-color: #f8f9fa;">
                                                <small class="text-muted d-block mb-2">Trainer</small>
                                                @if($class->trainer)
                                                    @php
                                                        $trainerName = $class->trainer->name ?? '';
                                                        $initials = '';
                                                        if ($trainerName !== '') {
                                                            $parts = preg_split('/\s+/', trim($trainerName));
                                                            if (!empty($parts[0])) { $initials .= mb_strtoupper(mb_substr($parts[0], 0, 1)); }
                                                            if (!empty($parts[1])) { $initials .= mb_strtoupper(mb_substr($parts[1], 0, 1)); }
                                                        }
                                                    @endphp
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-circle me-3" style="width: 42px; height: 42px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 50%; display: flex; align-items-center; justify-content: center; color: white; font-weight: 600; font-size: 0.9rem;">
                                                            {{ $initials !== '' ? $initials : '?' }}
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold">{{ $class->trainer->name }}</div>
                                                            @if($class->trainer->specialty)
                                                                <small class="text-muted">{{ $class->trainer->specialty }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-muted">No trainer assigned</span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Location -->
                                        <div class="col-12">
                                            <div class="p-3 rounded" style="background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);">
                                                <small class="text-muted d-block mb-1">Location</small>
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-geo-alt text-danger me-2 fs-5"></i>
                                                    <span class="fw-semibold">{{ $class->location ?: 'Not specified' }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Schedule -->
                                        <div class="col-12">
                                            <div class="p-3 rounded" style="background: linear-gradient(135deg, #a8edea15 0%, #fed6e315 100%);">
                                                <small class="text-muted d-block mb-2">Scheduled Time</small>
                                                <div class="mb-2">
                                                    <i class="bi bi-calendar-event text-primary me-2"></i>
                                                    <span class="fw-semibold">{{ $class->scheduled_at->format('l, M d, Y') }}</span>
                                                </div>
                                                <div class="mb-2">
                                                    <i class="bi bi-clock text-success me-2"></i>
                                                    <span class="fw-semibold">{{ $class->scheduled_at->format('h:i A') }}</span>
                                                </div>
                                                <small class="text-muted">{{ $class->scheduled_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Capacity -->
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                                <div class="card-header bg-white border-0 pb-0" style="padding: 1.5rem 1.5rem 1rem;">
                                    <h5 class="mb-0 fw-bold">
                                        <i class="bi bi-people text-success me-2"></i>Capacity & Attendees
                                    </h5>
                                </div>
                                <div class="card-body" style="padding: 1.5rem;">
                                    @php
                                        $attendees = $class->attendances()->count();
                                        $percentage = $class->capacity > 0 ? ($attendees / $class->capacity) * 100 : 0;
                                    @endphp
                                    <div class="text-center mb-4">
                                        <div class="mb-3">
                                            <span class="badge bg-{{ $percentage >= 100 ? 'danger' : ($percentage >= 80 ? 'warning' : 'success') }} bg-gradient" style="font-size: 1.5rem; padding: 1rem 2rem;">
                                                {{ $attendees }} / {{ $class->capacity }}
                                            </span>
                                        </div>
                                        @if($percentage >= 100)
                                            <div class="alert alert-danger mb-3">
                                                <i class="bi bi-exclamation-triangle-fill me-2"></i>Class is full
                                            </div>
                                        @elseif($percentage >= 80)
                                            <div class="alert alert-warning mb-3">
                                                <i class="bi bi-exclamation-circle-fill me-2"></i>Almost full - {{ $class->capacity - $attendees }} spots left
                                            </div>
                                        @else
                                            <div class="alert alert-success mb-3">
                                                <i class="bi bi-check-circle me-2"></i>{{ $class->capacity - $attendees }} spots available
                                            </div>
                                        @endif
                                    </div>

                                    <div class="progress" style="height: 30px; border-radius: 10px;">
                                        <div class="progress-bar bg-{{ $percentage >= 100 ? 'danger' : ($percentage >= 80 ? 'warning' : 'success') }} bg-gradient" role="progressbar" style="width: {{ min($percentage, 100) }}%">
                                            <span class="fw-semibold">{{ number_format($percentage, 0) }}%</span>
                                        </div>
                                    </div>

                                    <div class="row g-3 mt-3">
                                        <div class="col-6">
                                            <div class="p-3 rounded text-center" style="background-color: #f8f9fa;">
                                                <small class="text-muted d-block mb-1">Created</small>
                                                <small class="fw-semibold">{{ $class->created_at?->format('M d, Y') }}</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="p-3 rounded text-center" style="background-color: #f8f9fa;">
                                                <small class="text-muted d-block mb-1">Updated</small>
                                                <small class="fw-semibold">{{ $class->updated_at?->format('M d, Y') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Attendees List -->
                    @if($class->attendances->isNotEmpty())
                        <div class="card border-0 shadow-sm mt-4" style="border-radius: 12px;">
                            <div class="card-header bg-white border-0 pb-0" style="padding: 1.5rem 1.5rem 1rem;">
                                <h5 class="mb-0 fw-bold">
                                    <i class="bi bi-people-fill text-primary me-2"></i>Attendees ({{ $class->attendances->count() }})
                                </h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="list-group list-group-flush">
                                    @foreach($class->attendances as $attendance)
                                        @php
                                            $member = $attendance->member;
                                            $memberName = $member ? trim(($member->first_name ?? '') . ' ' . ($member->last_name ?? '')) : '';
                                            $initials = '';
                                            if ($memberName !== '') {
                                                $parts = preg_split('/\s+/', trim($memberName));
                                                if (!empty($parts[0])) { $initials .= mb_strtoupper(mb_substr($parts[0], 0, 1)); }
                                                if (!empty($parts[1])) { $initials .= mb_strtoupper(mb_substr($parts[1], 0, 1)); }
                                            }
                                        @endphp
                                        <div class="list-group-item d-flex justify-content-between align-items-center" style="padding: 1rem 1.5rem;">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle me-3" style="width: 40px; height: 40px; background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border-radius: 50%; display: flex; align-items-center; justify-content: center; color: white; font-weight: 600; font-size: 0.85rem;">
                                                    {{ $initials !== '' ? $initials : '?' }}
                                                </div>
                                                <div>
                                                    @if($member)
                                                        <div class="fw-semibold">
                                                            <a href="{{ route('members.show', $member) }}" class="text-decoration-none text-dark">
                                                                {{ $memberName ?: $member->email }}
                                                            </a>
                                                        </div>
                                                        @if($memberName && $member->email)
                                                            <small class="text-muted"><i class="bi bi-envelope me-1"></i>{{ $member->email }}</small>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">Unknown Member</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-light text-dark">
                                                    <i class="bi bi-clock me-1"></i>{{ $attendance->check_in_time?->format('h:i A') }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
