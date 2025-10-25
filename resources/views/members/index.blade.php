@extends('layouts.app')
@section('title', 'Members List')

@section('content')
<div class="container mt-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-2">
        <h2 class="fw-bold mb-0">👥 Members</h2>
        <div class="d-flex gap-2">
            <form action="{{ route('members.index') }}" method="GET" class="d-flex">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control me-2" placeholder="Search name, email, phone" />
                <button class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
            </form>
            <a href="{{ route('members.create') }}" class="btn btn-primary">
                <i class="bi bi-person-plus"></i> Add Member
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-3">
        @forelse ($members as $member)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card h-100 shadow-sm border-0">
                    <div class="position-relative">
                        <img src="{{ $member->photo_url }}" class="card-img-top" alt="{{ $member->name }}" style="height: 200px; object-fit: cover;">
                        <span class="position-absolute top-0 end-0 m-2 badge bg-primary">#{{ $member->id }}</span>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title mb-1">{{ $member->name }}</h5>
                        <p class="text-muted small mb-2"><i class="bi bi-envelope"></i> {{ $member->email }}</p>
                        <p class="text-muted small mb-2"><i class="bi bi-telephone"></i> {{ $member->phone ?: '—' }}</p>
                        <p class="text-muted small mb-3"><i class="bi bi-person-badge"></i> {{ $member->trainer->name ?? 'No trainer' }}</p>
                        <div class="mt-auto d-flex gap-2">
                            <a href="{{ route('members.show', $member) }}" class="btn btn-sm btn-outline-info flex-fill">
                                <i class="bi bi-eye"></i> View
                            </a>
                            <a href="{{ route('members.edit', $member) }}" class="btn btn-sm btn-outline-success flex-fill">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form action="{{ route('attendances.store') }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="member_id" value="{{ $member->id }}">
                                <button type="submit" class="btn btn-sm btn-outline-primary" title="Record Attendance" onclick="return confirm('Record check-in for {{ $member->name }}?')">
                                    <i class="bi bi-check2-circle"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0">
                        <small class="text-muted"><i class="bi bi-calendar"></i> Joined {{ \Carbon\Carbon::parse($member->join_date)->toFormattedDateString() }}</small>
                        <form action="{{ route('members.destroy', $member) }}" method="POST" class="d-inline float-end">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this member?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center text-muted py-3">No members found.</div>
            </div>
        @endforelse
    </div>

    <div class="mt-3 d-flex justify-content-between align-items-center">
        <div class="text-muted small">
            @if($members->total() > 0)
                Showing {{ $members->firstItem() }}–{{ $members->lastItem() }} of {{ $members->total() }}
                @if(request('q')) for "{{ request('q') }}" @endif
            @else
                No results @if(request('q')) for "{{ request('q') }}" @endif
            @endif
        </div>
        {{ $members->links() }}
    </div>
</div>
@endsection
