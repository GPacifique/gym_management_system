<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">Edit Attendance #{{ $attendance->id }}</h2>
            <a href="{{ route('attendances.show', $attendance) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Details
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <form action="{{ route('attendances.update', $attendance) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="member_id" class="form-label">Member <span class="text-danger">*</span></label>
                                    <select class="form-select @error('member_id') is-invalid @enderror" id="member_id" name="member_id" required>
                                        @foreach($members as $member)
                                            @php $fullName = trim(($member->first_name ?? '') . ' ' . ($member->last_name ?? '')); @endphp
                                            <option value="{{ $member->id }}" {{ old('member_id', $attendance->member_id) == $member->id ? 'selected' : '' }}>
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
                                            <option value="{{ $class->id }}" {{ old('class_id', $attendance->class_id) == $class->id ? 'selected' : '' }}>
                                                {{ $class->class_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('class_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="check_in_time" class="form-label">Check-in Time <span class="text-danger">*</span></label>
                                        <input type="datetime-local" class="form-control @error('check_in_time') is-invalid @enderror" id="check_in_time" name="check_in_time" value="{{ old('check_in_time', $attendance->check_in_time?->format('Y-m-d\TH:i')) }}" required>
                                        @error('check_in_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="check_out_time" class="form-label">Check-out Time</label>
                                        <input type="datetime-local" class="form-control @error('check_out_time') is-invalid @enderror" id="check_out_time" name="check_out_time" value="{{ old('check_out_time', $attendance->check_out_time?->format('Y-m-d\TH:i')) }}">
                                        @error('check_out_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="4">{{ old('notes', $attendance->notes) }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle"></i> Save Changes
                                    </button>
                                    <a href="{{ route('attendances.show', $attendance) }}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
