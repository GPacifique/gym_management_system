<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">Workout Plan #{{ $workoutPlan->id }}</h2>
            <div class="d-flex gap-2">
                <a href="{{ route('workout-plans.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Plans
                </a>
                <a href="{{ route('workout-plans.edit', $workoutPlan) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <form action="{{ route('workout-plans.destroy', $workoutPlan) }}" method="POST" onsubmit="return confirm('Delete this workout plan?');">
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
                            <span class="fw-semibold">{{ $workoutPlan->plan_name }}</span>
                            @if($workoutPlan->is_active)
                                <span class="badge bg-success">Active</span>
                            @elseif($workoutPlan->end_date && $workoutPlan->end_date < now())
                                <span class="badge bg-danger">Expired</span>
                            @elseif($workoutPlan->start_date && $workoutPlan->start_date > now())
                                <span class="badge bg-info">Upcoming</span>
                            @else
                                <span class="badge bg-secondary">Draft</span>
                            @endif
                        </div>
                        <div class="card-body">
                            <dl class="row mb-0">
                                <dt class="col-sm-3">Member</dt>
                                <dd class="col-sm-9">
                                    @php
                                        $member = $workoutPlan->member;
                                        $memberName = $member ? trim(($member->first_name ?? '') . ' ' . ($member->last_name ?? '')) : '';
                                    @endphp
                                    @if($member)
                                        <a href="{{ route('members.show', $member) }}" class="text-decoration-none fw-semibold">
                                            {{ $memberName ?: $member->email }}
                                        </a>
                                        @if($memberName && $member->email)
                                            <div class="text-muted small">{{ $member->email }}</div>
                                        @endif
                                    @else
                                        —
                                    @endif
                                </dd>

                                <dt class="col-sm-3">Trainer</dt>
                                <dd class="col-sm-9">
                                    @if($workoutPlan->trainer)
                                        <div class="fw-semibold">{{ $workoutPlan->trainer->name }}</div>
                                        @if($workoutPlan->trainer->specialty)
                                            <div class="text-muted small">{{ $workoutPlan->trainer->specialty }}</div>
                                        @endif
                                        @if($workoutPlan->trainer->email)
                                            <div class="text-muted small">{{ $workoutPlan->trainer->email }}</div>
                                        @endif
                                    @else
                                        —
                                    @endif
                                </dd>

                                <dt class="col-sm-3">Duration</dt>
                                <dd class="col-sm-9">
                                    @if($workoutPlan->start_date && $workoutPlan->end_date)
                                        <div>
                                            <i class="bi bi-calendar-check text-success"></i> 
                                            {{ $workoutPlan->start_date->format('M d, Y') }}
                                        </div>
                                        <div>
                                            <i class="bi bi-calendar-x text-danger"></i> 
                                            {{ $workoutPlan->end_date->format('M d, Y') }}
                                        </div>
                                        <div class="text-muted small mt-1">
                                            ({{ $workoutPlan->start_date->diffInDays($workoutPlan->end_date) }} days)
                                        </div>
                                    @else
                                        <span class="text-muted">Not set</span>
                                    @endif
                                </dd>

                                <dt class="col-sm-3">Created</dt>
                                <dd class="col-sm-9">{{ $workoutPlan->created_at?->format('M d, Y H:i') }}</dd>

                                <dt class="col-sm-3">Updated</dt>
                                <dd class="col-sm-9">{{ $workoutPlan->updated_at?->format('M d, Y H:i') }}</dd>
                            </dl>
                        </div>
                    </div>

                    @if($workoutPlan->description)
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-light fw-semibold">Description</div>
                            <div class="card-body">
                                <p class="mb-0" style="white-space: pre-line;">{{ $workoutPlan->description }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="card shadow-sm">
                        <div class="card-header bg-light fw-semibold">Actions</div>
                        <div class="card-body">
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('workout-plans.edit', $workoutPlan) }}" class="btn btn-outline-primary">
                                    <i class="bi bi-pencil"></i> Edit Plan
                                </a>
                                <a href="{{ route('workout-plans.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-list"></i> Back to List
                                </a>
                                @if($workoutPlan->member)
                                    <a href="{{ route('members.show', $workoutPlan->member) }}" class="btn btn-outline-info">
                                        <i class="bi bi-person"></i> View Member
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
