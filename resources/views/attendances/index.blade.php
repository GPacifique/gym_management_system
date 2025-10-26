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
        <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
            <div class="card-body p-0">
                <x-sophisticated-table :headers="['Date', 'Time', 'Member', 'Class', 'Status', 'Notes', 'Actions']">
                    @forelse($attendances as $attendance)
                        <tr>
                            <td>
                                <i class="bi bi-calendar3 text-primary me-1"></i>
                                <span class="fw-semibold">{{ $attendance->check_in_time->format('M d, Y') }}</span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">
                                    <i class="bi bi-clock me-1"></i>{{ $attendance->check_in_time->format('h:i A') }}
                                </span>
                            </td>
                            <td>
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
                                @if($member)
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-2" style="width: 32px; height: 32px; background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border-radius: 50%; display: flex; align-items-center; justify-content: center; color: white; font-weight: 600; font-size: 0.75rem;">
                                            {{ $initials !== '' ? $initials : '?' }}
                                        </div>
                                        <div>
                                            @hasAnyRole('admin','manager')
                                                <a href="{{ route('members.show', $member) }}" class="text-decoration-none text-dark fw-semibold">
                                                    {{ $memberName ?: ($member->email ?? 'Unknown') }}
                                                </a>
                                            @else
                                                <span class="fw-semibold">{{ $memberName ?: ($member->email ?? 'Unknown') }}</span>
                                            @endhasAnyRole
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($attendance->class_id)
                                    <span class="badge bg-info bg-gradient">
                                        <i class="bi bi-bookmark me-1"></i>{{ $attendance->class->class_name }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary bg-gradient">
                                        <i class="bi bi-door-open me-1"></i>Regular Visit
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($attendance->check_out_time)
                                    <span class="badge bg-success bg-gradient" style="font-size: 0.85rem;">
                                        <i class="bi bi-check-circle me-1"></i>Completed
                                    </span>
                                    <br>
                                    <small class="text-muted">Out: {{ $attendance->check_out_time->format('h:i A') }}</small>
                                @else
                                    <span class="badge bg-warning bg-gradient" style="font-size: 0.85rem;">
                                        <i class="bi bi-hourglass-split me-1"></i>In Progress
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($attendance->notes)
                                    <small class="text-muted">{{ Str::limit($attendance->notes, 30) }}</small>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    @if(!$attendance->check_out_time)
                                        <form action="{{ route('attendances.checkout', $attendance) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success" onclick="return confirm('Record checkout for this member?')" title="Checkout">
                                                <i class="bi bi-box-arrow-right"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <button type="button" class="btn btn-outline-info" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#viewAttendanceModal{{ $attendance->id }}"
                                            title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-clipboard-check" style="font-size: 4rem; opacity: 0.3;"></i>
                                    <p class="mt-3 mb-2 fs-5">No attendance records found</p>
                                    <p class="text-muted small mb-3">Start recording attendance for your members</p>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#recordAttendanceModal">
                                        <i class="bi bi-plus-circle me-1"></i> Record First Attendance
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </x-sophisticated-table>
                
                <div class="d-flex justify-content-end mt-3 px-3 pb-3">
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