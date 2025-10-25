<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">💪 Workout Plans</h2>
            <a href="{{ route('workout-plans.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Create Plan
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
                                    <th>Plan Name</th>
                                    <th>Member</th>
                                    <th>Trainer</th>
                                    <th>Duration</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($workoutPlans as $plan)
                                    <tr>
                                        <td>{{ $plan->id }}</td>
                                        <td class="fw-bold">{{ $plan->plan_name }}</td>
                                        <td>
                                            @php
                                                $member = $plan->member;
                                                $memberName = $member ? trim(($member->first_name ?? '') . ' ' . ($member->last_name ?? '')) : '';
                                            @endphp
                                            @if($member)
                                                <a href="{{ route('members.show', $member) }}" class="text-decoration-none">
                                                    {{ $memberName ?: $member->email }}
                                                </a>
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td>
                                            @if($plan->trainer)
                                                <span class="badge bg-secondary">{{ $plan->trainer->name }}</span>
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td>
                                            @if($plan->start_date && $plan->end_date)
                                                <small>
                                                    {{ $plan->start_date->format('M d, Y') }} 
                                                    <i class="bi bi-arrow-right"></i> 
                                                    {{ $plan->end_date->format('M d, Y') }}
                                                </small>
                                            @else
                                                <span class="text-muted">Not set</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($plan->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @elseif($plan->end_date && $plan->end_date < now())
                                                <span class="badge bg-danger">Expired</span>
                                            @elseif($plan->start_date && $plan->start_date > now())
                                                <span class="badge bg-info">Upcoming</span>
                                            @else
                                                <span class="badge bg-secondary">Draft</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('workout-plans.show', $plan) }}" class="btn btn-outline-primary" title="View">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('workout-plans.edit', $plan) }}" class="btn btn-outline-warning" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('workout-plans.destroy', $plan) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this workout plan?');">
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
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bi bi-journal-text" style="font-size: 3rem;"></i>
                                                <p class="mt-2">No workout plans found.</p>
                                                <a href="{{ route('workout-plans.create') }}" class="btn btn-primary btn-sm">
                                                    <i class="bi bi-plus-circle"></i> Create First Plan
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($workoutPlans->hasPages())
                        <div class="mt-4">
                            {{ $workoutPlans->links() }}
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
