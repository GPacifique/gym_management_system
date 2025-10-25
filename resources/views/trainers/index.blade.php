@extends('layouts.app')
@section('title', 'Trainers')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold">🏋️ Trainers</h2>
        <a href="{{ route('trainers.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus"></i> Add Trainer
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="table-responsive shadow-sm rounded">
        <table class="table table-hover table-striped align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Specialization</th>
                    <th>Salary</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($trainers as $trainer)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $trainer->name }}</td>
                        <td>{{ $trainer->email }}</td>
                        <td>{{ $trainer->phone }}</td>
                        <td>{{ $trainer->specialization ?? '—' }}</td>
                        <td>${{ number_format($trainer->salary ?? 0, 2) }}</td>
                        <td>
                            <a href="{{ route('trainers.show', $trainer) }}" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('trainers.edit', $trainer) }}" class="btn btn-sm btn-outline-success">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('trainers.destroy', $trainer) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this trainer?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-3">No trainers found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $trainers->links() }}
    </div>

    <div class="mt-4">
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
    </div>
</div>
@endsection
