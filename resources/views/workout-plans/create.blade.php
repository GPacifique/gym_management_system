<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">Create Workout Plan</h2>
            <a href="{{ route('workout-plans.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Plans
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <form action="{{ route('workout-plans.store') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label for="plan_name" class="form-label">Plan Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('plan_name') is-invalid @enderror" id="plan_name" name="plan_name" value="{{ old('plan_name') }}" required>
                                    @error('plan_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="member_id" class="form-label">Member <span class="text-danger">*</span></label>
                                    <select class="form-select @error('member_id') is-invalid @enderror" id="member_id" name="member_id" required>
                                        <option value="">Select a member</option>
                                        @foreach($members as $member)
                                            @php
                                                $fullName = trim(($member->first_name ?? '') . ' ' . ($member->last_name ?? ''));
                                            @endphp
                                            <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                                                {{ $fullName !== '' ? $fullName : ($member->email ?? 'Member #' . $member->id) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('member_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="trainer_id" class="form-label">Trainer <span class="text-danger">*</span></label>
                                    <select class="form-select @error('trainer_id') is-invalid @enderror" id="trainer_id" name="trainer_id" required>
                                        <option value="">Select a trainer</option>
                                        @foreach($trainers as $trainer)
                                            <option value="{{ $trainer->id }}" {{ old('trainer_id') == $trainer->id ? 'selected' : '' }}>
                                                {{ $trainer->name }}
                                                @if($trainer->specialty) - {{ $trainer->specialty }} @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('trainer_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Describe the workout plan, goals, exercises, etc.</div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="start_date" class="form-label">Start Date</label>
                                        <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date') }}">
                                        @error('start_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="end_date" class="form-label">End Date</label>
                                        <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date') }}">
                                        @error('end_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle"></i> Create Plan
                                    </button>
                                    <a href="{{ route('workout-plans.index') }}" class="btn btn-secondary">
                                        Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
