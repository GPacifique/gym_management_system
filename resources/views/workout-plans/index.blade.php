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

            <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                <div class="card-body p-0">
                    <x-sophisticated-table :headers="['ID', 'Plan Name', 'Member', 'Trainer', 'Duration', 'Status', 'Actions']">
                        @forelse($workoutPlans as $plan)
                            <tr>
                                <td><span class="badge bg-light text-dark">#{{ $plan->id }}</span></td>
                                <td>
                                    <div class="fw-bold text-dark" style="font-size: 1.05rem;">
                                        <i class="bi bi-journal-text text-primary me-1"></i>{{ $plan->plan_name }}
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $member = $plan->member;
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
                                            <div class="avatar-circle me-2" style="width: 32px; height: 32px; background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); border-radius: 50%; display: flex; align-items-center; justify-content: center; color: #333; font-weight: 600; font-size: 0.75rem;">
                                                {{ $initials !== '' ? $initials : '?' }}
                                            </div>
                                            <a href="{{ route('members.show', $member) }}" class="text-decoration-none text-dark fw-semibold">
                                                {{ $memberName ?: $member->email }}
                                            </a>
                                        </div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($plan->trainer)
                                        @php
                                            $trainerName = $plan->trainer->name ?? '';
                                            $tInitials = '';
                                            if ($trainerName !== '') {
                                                $parts = preg_split('/\s+/', trim($trainerName));
                                                if (!empty($parts[0])) { $tInitials .= mb_strtoupper(mb_substr($parts[0], 0, 1)); }
                                                if (!empty($parts[1])) { $tInitials .= mb_strtoupper(mb_substr($parts[1], 0, 1)); }
                                            }
                                        @endphp
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-2" style="width: 28px; height: 28px; background: linear-gradient(135deg, #89f7fe 0%, #66a6ff 100%); border-radius: 50%; display: flex; align-items-center; justify-content: center; color: white; font-weight: 600; font-size: 0.7rem;">
                                                {{ $tInitials !== '' ? $tInitials : '?' }}
                                            </div>
                                            <span class="text-dark">{{ $plan->trainer->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($plan->start_date && $plan->end_date)
                                        <small>
                                            <i class="bi bi-calendar-check text-success me-1"></i>{{ $plan->start_date->format('M d, Y') }}
                                            <br>
                                            <i class="bi bi-calendar-x text-danger me-1"></i>{{ $plan->end_date->format('M d, Y') }}
                                        </small>
                                    @else
                                        <span class="text-muted">Not set</span>
                                    @endif
                                </td>
                                <td>
                                    @if($plan->is_active)
                                        <span class="badge bg-success bg-gradient" style="font-size: 0.85rem;">
                                            <i class="bi bi-check-circle me-1"></i>Active
                                        </span>
                                    @elseif($plan->end_date && $plan->end_date < now())
                                        <span class="badge bg-danger bg-gradient" style="font-size: 0.85rem;">
                                            <i class="bi bi-x-circle me-1"></i>Expired
                                        </span>
                                    @elseif($plan->start_date && $plan->start_date > now())
                                        <span class="badge bg-info bg-gradient" style="font-size: 0.85rem;">
                                            <i class="bi bi-clock-history me-1"></i>Upcoming
                                        </span>
                                    @else
                                        <span class="badge bg-secondary bg-gradient" style="font-size: 0.85rem;">
                                            <i class="bi bi-file-earmark me-1"></i>Draft
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('workout-plans.show', $plan) }}" class="btn btn-outline-primary" title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('workout-plans.edit', $plan) }}" class="btn btn-outline-warning" title="Edit Plan">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('workout-plans.destroy', $plan) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this workout plan?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Delete Plan">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-journal-text" style="font-size: 4rem; opacity: 0.3;"></i>
                                        <p class="mt-3 mb-2 fs-5">No workout plans found</p>
                                        <p class="text-muted small mb-3">Create personalized workout plans for your members</p>
                                        <a href="{{ route('workout-plans.create') }}" class="btn btn-primary">
                                            <i class="bi bi-plus-circle me-1"></i> Create First Plan
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </x-sophisticated-table>

                    @if($workoutPlans->hasPages())
                        <div class="mt-4 px-3 pb-3">
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
