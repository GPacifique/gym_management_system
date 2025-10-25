<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">Attendance #{{ $attendance->id }}</h2>
            <div class="d-flex gap-2">
                <a href="{{ route('attendances.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Records
                </a>
                @hasAnyRole('admin','manager')
                    <a href="{{ route('attendances.edit', $attendance) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <form action="{{ route('attendances.destroy', $attendance) }}" method="POST" onsubmit="return confirm('Delete this attendance record?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </form>
                @endhasAnyRole
            </div>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light fw-semibold">Overview</div>
                        <div class="card-body">
                            <dl class="row mb-0">
                                <dt class="col-sm-3">Member</dt>
                                <dd class="col-sm-9">
                                    @php($member = $attendance->member)
                                    @if($member)
                                        @php($memberName = trim(($member->first_name ?? '') . ' ' . ($member->last_name ?? '')))
                                        @hasAnyRole('admin','manager')
                                            <a href="{{ route('members.show', $member) }}" class="text-decoration-none">
                                                {{ $memberName ?: ($member->email ?? 'Unknown') }}
                                            </a>
                                        @else
                                            {{ $memberName ?: ($member->email ?? 'Unknown') }}
                                        @endhasAnyRole
                                        @if($memberName && $member->email)
                                            <div class="text-muted small">{{ $member->email }}</div>
                                        @endif
                                    @else
                                        —
                                    @endif
                                </dd>

                                <dt class="col-sm-3">Class</dt>
                                <dd class="col-sm-9">
                                    @if($attendance->class)
                                        <div class="fw-semibold">{{ $attendance->class->class_name }}</div>
                                        @if($attendance->class->trainer)
                                            <small class="text-muted">with {{ $attendance->class->trainer->name }}</small>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">Regular Visit</span>
                                    @endif
                                </dd>

                                <dt class="col-sm-3">Check-in</dt>
                                <dd class="col-sm-9">{{ $attendance->check_in_time->format('M d, Y h:i A') }}</dd>

                                <dt class="col-sm-3">Check-out</dt>
                                <dd class="col-sm-9">
                                    @if($attendance->check_out_time)
                                        {{ $attendance->check_out_time->format('M d, Y h:i A') }}
                                        <div class="text-muted small">Duration: {{ $attendance->check_in_time->diffForHumans($attendance->check_out_time, true) }}</div>
                                    @else
                                        <span class="badge bg-warning text-dark">In Progress</span>
                                    @endif
                                </dd>

                                @if($attendance->notes)
                                    <dt class="col-sm-3">Notes</dt>
                                    <dd class="col-sm-9">{{ $attendance->notes }}</dd>
                                @endif

                                <dt class="col-sm-3">Created</dt>
                                <dd class="col-sm-9">{{ $attendance->created_at?->format('M d, Y H:i') }}</dd>

                                <dt class="col-sm-3">Updated</dt>
                                <dd class="col-sm-9">{{ $attendance->updated_at?->format('M d, Y H:i') }}</dd>
                            </dl>
                        </div>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-header bg-light fw-semibold">Actions</div>
                        <div class="card-body d-flex flex-wrap gap-2">
                            @if(!$attendance->check_out_time)
                                <form action="{{ route('attendances.checkout', $attendance) }}" method="POST" onsubmit="return confirm('Record checkout now?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-box-arrow-right"></i> Checkout Now
                                    </button>
                                </form>
                            @endif
                            @hasAnyRole('admin','manager')
                                <a href="{{ route('attendances.edit', $attendance) }}" class="btn btn-outline-primary">
                                    <i class="bi bi-pencil"></i> Edit Attendance
                                </a>
                            @endhasAnyRole
                            <a href="{{ route('attendances.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-list"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
