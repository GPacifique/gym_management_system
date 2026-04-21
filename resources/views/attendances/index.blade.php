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
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- TABLE -->
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <x-sophisticated-table :headers="['Date','Time','Member','Class','Status','Notes','Actions']">
                    
                    @forelse($attendances as $attendance)
                        <tr>
                            <!-- DATE -->
                            <td>{{ $attendance->check_in_time->format('M d, Y') }}</td>

                            <!-- TIME -->
                            <td>{{ $attendance->check_in_time->format('h:i A') }}</td>

                            <!-- MEMBER -->
                            <td>
                                {{ $attendance->member?->first_name }}
                                {{ $attendance->member?->last_name ?? $attendance->member?->email ?? 'Unknown' }}
                            </td>

                            <!-- ✅ CLASS (FIXED) -->
                            <td>
                                @if($attendance->class)
                                    <span class="badge bg-info">
                                        {{ $attendance->class->class_name }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        Regular Visit
                                    </span>
                                @endif
                            </td>

                            <!-- STATUS -->
                            <td>
                                @if($attendance->check_out_time)
                                    <span class="badge bg-success">Completed</span>
                                @else
                                    <span class="badge bg-warning">In Progress</span>
                                @endif
                            </td>

                            <!-- NOTES -->
                            <td>
                                {{ Str::limit($attendance->notes, 30) ?? '—' }}
                            </td>

                            <!-- ACTIONS -->
                            <td>
                                @if(!$attendance->check_out_time)
                                    <form action="{{ route('attendances.checkout', $attendance) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn btn-success btn-sm">Checkout</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                No attendance records found
                            </td>
                        </tr>
                    @endforelse

                </x-sophisticated-table>

                <div class="p-3">
                    {{ $attendances->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL (FIXED) -->
    @foreach($attendances as $attendance)
        <div class="modal fade" id="viewAttendanceModal{{ $attendance->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">

                        <p><strong>Member:</strong>
                            {{ $attendance->member?->first_name }}
                            {{ $attendance->member?->last_name ?? 'Unknown' }}
                        </p>

                        <p><strong>Check-in:</strong>
                            {{ $attendance->check_in_time }}
                        </p>

                        <p><strong>Class:</strong>
                            {{ $attendance->class?->class_name ?? 'Regular Visit' }}
                        </p>

                    </div>
                </div>
            </div>
        </div>
    @endforeach

</x-app-layout>