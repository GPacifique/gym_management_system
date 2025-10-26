<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">🏋️ Gym Classes</h2>
            <a href="{{ route('classes.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Create Class
            </a>
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

            <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                <div class="card-body p-0">
                    <x-sophisticated-table :headers="['ID', 'Class Name', 'Trainer', 'Location', 'Scheduled', 'Capacity', 'Status', 'Actions']">
                        @forelse($classes as $class)
                            @php
                                $attendees = $class->attendances()->count();
                                $percentage = $class->capacity > 0 ? ($attendees / $class->capacity) * 100 : 0;
                            @endphp
                            <tr class="{{ $class->scheduled_at->isPast() && !$class->scheduled_at->isToday() ? 'table-light opacity-75' : '' }}">
                                <td><span class="badge bg-light text-dark">#{{ $class->id }}</span></td>
                                <td>
                                    <div class="fw-bold text-dark" style="font-size: 1.05rem;">
                                        <i class="bi bi-bookmark-star text-primary me-1"></i>{{ $class->class_name }}
                                    </div>
                                </td>
                                <td>
                                    @if($class->trainer)
                                        <div class="d-flex align-items-center">
                                            @php
                                                $trainerName = $class->trainer->name ?? '';
                                                $initials = '';
                                                if ($trainerName !== '') {
                                                    $parts = preg_split('/\s+/', trim($trainerName));
                                                    if (!empty($parts[0])) { $initials .= mb_strtoupper(mb_substr($parts[0], 0, 1)); }
                                                    if (!empty($parts[1])) { $initials .= mb_strtoupper(mb_substr($parts[1], 0, 1)); }
                                                }
                                            @endphp
                                            <div class="avatar-circle me-2" style="width: 32px; height: 32px; background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #333; font-weight: 600; font-size: 0.75rem;">
                                                {{ $initials !== '' ? $initials : '?' }}
                                            </div>
                                            <span class="text-dark">{{ $class->trainer->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($class->location)
                                        <span class="badge bg-secondary bg-gradient">
                                            <i class="bi bi-geo-alt me-1"></i>{{ $class->location }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <div>
                                        <i class="bi bi-calendar3 text-primary me-1"></i>
                                        <span class="fw-semibold">{{ $class->scheduled_at->format('M d, Y') }}</span>
                                    </div>
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i>{{ $class->scheduled_at->format('h:i A') }}
                                    </small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-{{ $percentage >= 100 ? 'danger' : ($percentage >= 80 ? 'warning' : 'success') }} bg-gradient">
                                            {{ $attendees }}/{{ $class->capacity }}
                                        </span>
                                        @if($percentage >= 100)
                                            <i class="bi bi-exclamation-triangle-fill text-danger" title="Full"></i>
                                        @elseif($percentage >= 80)
                                            <i class="bi bi-exclamation-circle-fill text-warning" title="Almost Full"></i>
                                        @endif
                                    </div>
                                    <small class="text-muted">{{ number_format($percentage, 0) }}% full</small>
                                </td>
                                <td>
                                    @if($class->scheduled_at->isFuture())
                                        <span class="badge bg-info bg-gradient">
                                            <i class="bi bi-clock-history me-1"></i>Upcoming
                                        </span>
                                    @elseif($class->scheduled_at->isToday())
                                        <span class="badge bg-success bg-gradient">
                                            <i class="bi bi-calendar-check me-1"></i>Today
                                        </span>
                                    @else
                                        <span class="badge bg-secondary bg-gradient">
                                            <i class="bi bi-calendar-x me-1"></i>Past
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('classes.show', $class) }}" class="btn btn-outline-primary" title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('classes.edit', $class) }}" class="btn btn-outline-warning" title="Edit Class">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('classes.destroy', $class) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this class?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Delete Class">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-calendar-event" style="font-size: 4rem; opacity: 0.3;"></i>
                                        <p class="mt-3 mb-2 fs-5">No classes found</p>
                                        <p class="text-muted small mb-3">Start by creating your first gym class</p>
                                        <a href="{{ route('classes.create') }}" class="btn btn-primary">
                                            <i class="bi bi-plus-circle me-1"></i> Create First Class
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </x-sophisticated-table>

                    @if($classes->hasPages())
                        <div class="mt-4 px-3">
                            {{ $classes->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .btn-group-sm .btn {
            padding: 0.25rem 0.5rem;
        }
    </style>
</x-app-layout>
