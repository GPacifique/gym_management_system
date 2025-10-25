@extends('layouts.app')
@section('title', 'View Member')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">{{ $member->name ?? ($member->first_name . ' ' . $member->last_name) }}</h4>
        </div>
        <div class="card-body">
            <p><strong>Email:</strong> {{ $member->email }}</p>
            <p><strong>Phone:</strong> {{ $member->phone }}</p>
            <p><strong>Gender:</strong> {{ ucfirst($member->gender) }}</p>
            <p><strong>Date of Birth:</strong> {{ $member->dob }}</p>
            <p><strong>Address:</strong> {{ $member->address }}</p>
            <p><strong>Trainer:</strong> {{ $member->trainer->name ?? 'Unassigned' }}</p>
            <p><strong>Join Date:</strong> {{ $member->join_date }}</p>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('members.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back</a>
            <div>
                <form action="{{ route('attendances.store') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="member_id" value="{{ $member->id }}">
                    <button type="submit" class="btn btn-outline-primary me-2" title="Record Attendance"
                        onclick="return confirm('Record check-in for {{ $member->first_name }} {{ $member->last_name }}?')">
                        <i class="bi bi-check2-circle"></i> Record Attendance
                    </button>
                </form>
                <a href="{{ route('members.edit', $member) }}" class="btn btn-success"><i class="bi bi-pencil"></i> Edit</a>
            </div>
        </div>
    </div>
</div>
@endsection
