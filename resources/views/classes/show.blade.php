<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">Class #{{ $class->id }} - {{ $class->class_name }}</h2>
            <div class="d-flex gap-2">
                <a href="{{ route('classes.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Classes
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
                <div class="col-lg-8">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">Class Details</span>
                            @if($class->scheduled_at->isFuture())
                                <span class="badge bg-info">Upcoming</span>
                            @elseif($class->scheduled_at->isToday())
                                <span class="badge bg-success">Today</span>
                            @else
                                <span class="badge bg-secondary">Past</span>
                            @endif
                        </div>
                        <div class="card-body">
                            <dl class="row mb-0">
                                <dt class="col-sm-3">Class Name</dt>
                                <dd class="col-sm-9 fw-bold">{{ $class->class_name }}</dd>

                                <dt class="col-sm-3">Trainer</dt>
                                <dd class="col-sm-9">
                                    @if($class->trainer)
                                        <div class="fw-semibold">{{ $class->trainer->name }}</div>
                                        @if($class->trainer->specialty)
                                            <div class="text-muted small">{{ $class->trainer->specialty }}</div>
                                        @endif
                                        @if($class->trainer->email)
                                            <div class="text-muted small">{{ $class->trainer->email }}</div>
                                        @endif
                                    @else
                                        —
                                    @endif
                                </dd>

                                <dt class="col-sm-3">Location</dt>
                                <dd class="col-sm-9">{{ $class->location ?: '—' }}</dd>

                                <dt class="col-sm-3">Scheduled</dt>
                                <dd class="col-sm-9">
                                    <div><i class="bi bi-calendar-check"></i> {{ $class->scheduled_at->format('l, M d, Y') }}</div>
                                    <div><i class="bi bi-clock"></i> {{ $class->scheduled_at->format('h:i A') }}</div>
                                    <div class="text-muted small mt-1">{{ $class->scheduled_at->diffForHumans() }}</div>
                                </dd>

                                <dt class="col-sm-3">Capacity</dt>
                                <dd class="col-sm-9">
                                    @php
                                        $attendees = $class->attendances()->count();
                                        $percentage = $class->capacity > 0 ? ($attendees / $class->capacity) * 100 : 0;
                                    @endphp
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-{{ $percentage >= 100 ? 'danger' : ($percentage >= 80 ? 'warning' : 'success') }} fs-6">
                                            {{ $attendees }} / {{ $class->capacity }}
                                        </span>
                                        @if($percentage >= 100)
                                            <span class="text-danger"><i class="bi bi-exclamation-triangle-fill"></i> Full</span>
                                        @elseif($percentage >= 80)
                                            <span class="text-warning"><i class="bi bi-exclamation-circle-fill"></i> Almost Full</span>
                                        @else
                                            <span class="text-success">{{ $class->capacity - $attendees }} spots available</span>
                                        @endif
                                    </div>
                                    <div class="progress mt-2" style="height: 8px;">
                                        <div class="progress-bar bg-{{ $percentage >= 100 ? 'danger' : ($percentage >= 80 ? 'warning' : 'success') }}" role="progressbar" style="width: {{ min($percentage, 100) }}%"></div>
                                    </div>
                                </dd>

                                <dt class="col-sm-3">Created</dt>
                                <dd class="col-sm-9">{{ $class->created_at?->format('M d, Y H:i') }}</dd>

                                <dt class="col-sm-3">Updated</dt>
                                <dd class="col-sm-9">{{ $class->updated_at?->format('M d, Y H:i') }}</dd>
                            </dl>
                        </div>
                    </div>

                    @if($class->attendances->isNotEmpty())
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-light fw-semibold">
                                Attendees ({{ $class->attendances->count() }})
                            </div>
                            <div class="card-body">
                                <div class="list-group list-group-flush">
                                    @foreach($class->attendances as $attendance)
                                        @php
                                            $member = $attendance->member;
                                            $memberName = $member ? trim(($member->first_name ?? '') . ' ' . ($member->last_name ?? '')) : '';
                                        @endphp
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                @if($member)
                                                    <div class="fw-semibold">
                                                        <a href="{{ route('members.show', $member) }}" class="text-decoration-none">
                                                            {{ $memberName ?: $member->email }}
                                                        </a>
                                                    </div>
                                                    @if($memberName && $member->email)
                                                        <small class="text-muted">{{ $member->email }}</small>
                                                    @endif
                                                @else
                                                    <span class="text-muted">Unknown Member</span>
                                                @endif
                                            </div>
                                            <div class="text-end">
                                                <small class="text-muted">
                                                    <i class="bi bi-clock"></i> {{ $attendance->check_in_time?->format('h:i A') }}
                                                </small>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="card shadow-sm">
                        <div class="card-header bg-light fw-semibold">Actions</div>
                        <div class="card-body">
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('classes.edit', $class) }}" class="btn btn-outline-primary">
                                    <i class="bi bi-pencil"></i> Edit Class
                                </a>
                                <a href="{{ route('classes.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-list"></i> Back to List
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
