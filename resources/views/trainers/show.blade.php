@extends('layouts.app')
@section('title', 'Trainer Details')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h4 class="mb-0">{{ $trainer->name }}</h4>
        </div>
        <div class="card-body">
            <p><strong>Email:</strong> {{ $trainer->email }}</p>
            <p><strong>Phone:</strong> {{ $trainer->phone }}</p>
            <p><strong>Specialization:</strong> {{ $trainer->specialization ?? 'N/A' }}</p>
            <p><strong>Salary:</strong> ${{ number_format($trainer->salary ?? 0, 2) }}</p>
            <p><strong>Joined On:</strong> {{ $trainer->created_at->format('d M Y') }}</p>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('trainers.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
            <a href="{{ route('trainers.edit', $trainer) }}" class="btn btn-success">
                <i class="bi bi-pencil"></i> Edit
            </a>
        </div>
    </div>
</div>
@endsection
