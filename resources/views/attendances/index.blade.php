<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">📋 Attendance Records</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#recordAttendanceModal">
                <i class="bi bi-plus-circle"></i> Record Attendance
            </button>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('attendances.index') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="date" name="date" value="{{ request('date') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="member" class="form-label">Member</label>
                        <select class="form-select" id="member" name="member_id">
                            <option value="">All Members</option>
                            @foreach($members as $member)
                                @php
                                    $memberName = trim(($member->first_name ?? '') . ' ' . ($member->last_name ?? ''));
                                @endphp
                                <option value="{{ $member->id }}" {{ request('member_id') == $member->id ? 'selected' : '' }}>
                                    {{ $memberName ?: ($member->email ?? 'Member #' . $member->id) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="class" class="form-label">Class</label>
                        <select class="form-select" id="class" name="class_id">
                            <option value="">All Classes</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->class_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-2"></i>Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Attendance Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Member</th>
                                <th>Class</th>
                                <th>Status</th>
                                <th>Notes</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendances as $attendance)
                                <tr>
                                    <td>{{ $attendance->check_in_time->format('M d, Y') }}</td>
                                    <td>{{ $attendance->check_in_time->format('h:i A') }}</td>
                                    <td>
                                        @php
                                            $member = $attendance->member;
                                            $memberName = $member ? trim(($member->first_name ?? '') . ' ' . ($member->last_name ?? '')) : '';
                                        @endphp
                                        @if($member)
                                            @hasAnyRole('admin','manager')
                                                <a href="{{ route('members.show', $member) }}" class="text-decoration-none">
                                                    {{ $memberName ?: ($member->email ?? 'Unknown') }}
                                                </a>
                                            @else
                                                {{ $memberName ?: ($member->email ?? 'Unknown') }}
                                            @endhasAnyRole
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>
                                        @if($attendance->class_id)
                                            {{ $attendance->class->class_name }}
                                        @else
                                            Regular Visit
                                        @endif
                                    </td>
                                    <td>
                                        @if($attendance->check_out_time)
                                            <span class="badge bg-success">Completed</span>
                                        @else
                                            <span class="badge bg-warning">In Progress</span>
                                        @endif
                                    </td>
                                    <td>{{ $attendance->notes }}</td>
                                    <td>
                                        @if(!$attendance->check_out_time)
                                            <form action="{{ route('attendances.checkout', $attendance) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Record checkout for this member?')">
                                                    <i class="bi bi-box-arrow-right"></i> Checkout
                                                </button>
                                            </form>
                                        @endif
                                        <button type="button" class="btn btn-sm btn-info" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#viewAttendanceModal{{ $attendance->id }}">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No attendance records found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-end mt-3">
                    {{ $attendances->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Record Attendance Modal -->
    <div class="modal fade" id="recordAttendanceModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Record Attendance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('attendances.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="member_id" class="form-label">Member <span class="text-danger">*</span></label>
                            <select class="form-select @error('member_id') is-invalid @enderror" id="member_id" name="member_id" required>
                                <option value="">Select a member</option>
                                @foreach($members as $member)
                                    @php
                                        $fullName = trim(($member->first_name ?? '') . ' ' . ($member->last_name ?? ''));
                                    @endphp
                                    <option value="{{ $member->id }}">
                                        {{ $fullName ?: ($member->email ?? 'Member #' . $member->id) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('member_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="class_id" class="form-label">Class (Optional)</label>
                            <select class="form-select @error('class_id') is-invalid @enderror" id="class_id" name="class_id">
                                <option value="">Regular Visit</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                @endforeach
                            </select>
                            @error('class_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" 
                                      name="notes" 
                                      rows="3"
                                      placeholder="Any special notes or observations">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Record Check-in</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Individual Attendance View Modals -->
    @foreach($attendances as $attendance)
        <div class="modal fade" id="viewAttendanceModal{{ $attendance->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Attendance Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                        <div class="modal-body">
                        <div class="mb-3">
                                <h6 class="fw-semibold">Member</h6>
                                @php
                                    $member = $attendance->member;
                                    $memberName = $member ? trim(($member->first_name ?? '') . ' ' . ($member->last_name ?? '')) : '';
                                @endphp
                                <p>{{ $memberName ?: ($member?->email ?? 'Unknown') }}</p>
                        </div>
                        <div class="mb-3">
                            <h6>Check-in Time</h6>
                            <p>{{ $attendance->check_in_time->format('Y-m-d H:i:s') }}</p>
                        </div>
                        @if($attendance->check_out_time)
                            <div class="mb-3">
                                <h6>Check-out Time</h6>
                                <p>{{ $attendance->check_out_time->format('Y-m-d H:i:s') }}</p>
                            </div>
                            <div class="mb-3">
                                <h6>Duration</h6>
                                <p>{{ $attendance->check_in_time->diffForHumans($attendance->check_out_time, true) }}</p>
                            </div>
                        @endif
                        @if($attendance->class_id)
                            <div class="mb-3">
                                <h6 class="fw-semibold">Class</h6>
                                <p>{{ $attendance->class->class_name }}</p>
                            </div>
                        @endif
                        @if($attendance->notes)
                            <div class="mb-3">
                                <h6 class="fw-semibold">Notes</h6>
                                <p>{{ $attendance->notes }}</p>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</x-app-layout>