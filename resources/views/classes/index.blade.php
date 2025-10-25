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

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Class Name</th>
                                    <th>Trainer</th>
                                    <th>Location</th>
                                    <th>Scheduled</th>
                                    <th>Capacity</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($classes as $class)
                                    <tr>
                                        <td>{{ $class->id }}</td>
                                        <td class="fw-bold">{{ $class->class_name }}</td>
                                        <td>
                                            @if($class->trainer)
                                                <span class="badge bg-secondary">{{ $class->trainer->name }}</span>
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td>{{ $class->location ?: '—' }}</td>
                                        <td>
                                            <div>{{ $class->scheduled_at->format('M d, Y') }}</div>
                                            <small class="text-muted">{{ $class->scheduled_at->format('h:i A') }}</small>
                                        </td>
                                        <td>
                                            @php
                                                $attendees = $class->attendances()->count();
                                                $percentage = $class->capacity > 0 ? ($attendees / $class->capacity) * 100 : 0;
                                            @endphp
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge bg-{{ $percentage >= 100 ? 'danger' : ($percentage >= 80 ? 'warning' : 'success') }}">
                                                    {{ $attendees }}/{{ $class->capacity }}
                                                </span>
                                                @if($percentage >= 100)
                                                    <i class="bi bi-exclamation-triangle-fill text-danger" title="Full"></i>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @if($class->scheduled_at->isFuture())
                                                <span class="badge bg-info">Upcoming</span>
                                            @elseif($class->scheduled_at->isToday())
                                                <span class="badge bg-success">Today</span>
                                            @else
                                                <span class="badge bg-secondary">Past</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('classes.show', $class) }}" class="btn btn-outline-primary" title="View">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('classes.edit', $class) }}" class="btn btn-outline-warning" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('classes.destroy', $class) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this class?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bi bi-calendar-event" style="font-size: 3rem;"></i>
                                                <p class="mt-2">No classes found.</p>
                                                <a href="{{ route('classes.create') }}" class="btn btn-primary btn-sm">
                                                    <i class="bi bi-plus-circle"></i> Create First Class
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($classes->hasPages())
                        <div class="mt-4">
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
