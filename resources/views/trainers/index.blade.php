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

    <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
        <div class="card-body p-0">
            <x-sophisticated-table :headers="['#', 'Trainer', 'Contact', 'Specialization', 'Salary', 'Actions']">
                @forelse ($trainers as $trainer)
                    <tr>
                        <td><span class="badge bg-light text-dark">#{{ $loop->iteration }}</span></td>
                        <td>
                            <div class="d-flex align-items-center">
                                @php
                                    $name = $trainer->name ?? '';
                                    $initials = '';
                                    if ($name !== '') {
                                        $parts = preg_split('/\s+/', trim($name));
                                        if (!empty($parts[0])) { $initials .= mb_strtoupper(mb_substr($parts[0], 0, 1)); }
                                        if (!empty($parts[1])) { $initials .= mb_strtoupper(mb_substr($parts[1], 0, 1)); }
                                    }
                                @endphp
                                <div class="avatar-circle me-3" style="width: 42px; height: 42px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.9rem;">
                                    {{ $initials !== '' ? $initials : '?' }}
                                </div>
                                <div>
                                    <div class="fw-semibold text-dark">{{ $name !== '' ? $name : 'N/A' }}</div>
                                    <small class="text-muted"><i class="bi bi-person-badge me-1"></i>Trainer</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div><i class="bi bi-envelope text-primary me-2"></i>{{ $trainer->email }}</div>
                                <small class="text-muted"><i class="bi bi-telephone text-success me-2"></i>{{ $trainer->phone }}</small>
                            </div>
                        </td>
                        <td>
                            @if($trainer->specialization)
                                <span class="badge bg-info bg-gradient">
                                    <i class="bi bi-award me-1"></i>{{ $trainer->specialization }}
                                </span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="fw-bold text-success" style="font-size: 1.05rem;">
                                RWF {{ number_format($trainer->salary ?? 0, 2) }}
                            </span>
                            <br>
                            <small class="text-muted">Monthly</small>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('trainers.show', $trainer) }}" class="btn btn-outline-info" title="View Details">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('trainers.edit', $trainer) }}" class="btn btn-outline-warning" title="Edit Trainer">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('trainers.destroy', $trainer) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this trainer?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Delete Trainer">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-people" style="font-size: 4rem; opacity: 0.3;"></i>
                                <p class="mt-3 mb-2 fs-5">No trainers found</p>
                                <p class="text-muted small mb-3">Start by adding your first trainer to the team</p>
                                <a href="{{ route('trainers.create') }}" class="btn btn-primary">
                                    <i class="bi bi-person-plus me-1"></i> Add First Trainer
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </x-sophisticated-table>
        </div>
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
