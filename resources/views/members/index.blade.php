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

    <div class="row g-4">
        @forelse ($members as $member)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card h-100 border-0 sophisticated-card" style="border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: all 0.3s ease;">
                    <div class="position-relative" style="overflow: hidden;">
                        <img src="{{ $member->photo_url }}" class="card-img-top" alt="{{ $member->name }}" style="height: 220px; object-fit: cover; transition: transform 0.3s ease;">
                        <span class="position-absolute top-0 end-0 m-2 badge bg-primary bg-gradient shadow-sm" style="font-size: 0.85rem;">#{{ $member->id }}</span>
                        <div class="position-absolute bottom-0 start-0 end-0 p-3" style="background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, transparent 100%);">
                            <h5 class="text-white mb-0 fw-bold">{{ $member->name }}</h5>
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column" style="padding: 1.25rem;">
                        <div class="mb-3">
                            <p class="text-muted small mb-2 d-flex align-items-center">
                                <i class="bi bi-envelope text-primary me-2"></i>
                                <span class="text-truncate">{{ $member->email }}</span>
                            </p>
                            <p class="text-muted small mb-2 d-flex align-items-center">
                                <i class="bi bi-telephone text-success me-2"></i>
                                <span>{{ $member->phone ?: '—' }}</span>
                            </p>
                            @if($member->trainer)
                                <p class="small mb-0 d-flex align-items-center">
                                    <i class="bi bi-person-badge text-info me-2"></i>
                                    <span class="badge bg-info bg-gradient">{{ $member->trainer->name }}</span>
                                </p>
                            @else
                                <p class="text-muted small mb-0 d-flex align-items-center">
                                    <i class="bi bi-person-badge text-secondary me-2"></i>
                                    <span class="text-secondary">No trainer assigned</span>
                                </p>
                            @endif
                        </div>
                        <div class="mt-auto">
                            <div class="d-grid gap-2 mb-2">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('members.show', $member) }}" class="btn btn-sm btn-outline-info" title="View Details">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <a href="{{ route('members.edit', $member) }}" class="btn btn-sm btn-outline-warning" title="Edit Member">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                </div>
                            </div>
                            <form action="{{ route('attendances.store') }}" method="POST" class="d-grid">
                                @csrf
                                <input type="hidden" name="member_id" value="{{ $member->id }}">
                                <button type="submit" class="btn btn-sm btn-primary bg-gradient" onclick="return confirm('Record check-in for {{ $member->name }}?')">
                                    <i class="bi bi-check2-circle me-1"></i> Check In
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between align-items-center" style="background: linear-gradient(to right, #f8f9fa 0%, #e9ecef 100%); border-top: 1px solid rgba(0,0,0,0.05); padding: 0.75rem 1.25rem;">
                        <small class="text-muted">
                            <i class="bi bi-calendar-event me-1"></i>
                            {{ \Carbon\Carbon::parse($member->join_date)->diffForHumans() }}
                        </small>
                        <form action="{{ route('members.destroy', $member) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete {{ $member->name }}?')" title="Delete Member">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-people" style="font-size: 4rem; opacity: 0.3; color: #6c757d;"></i>
                        <p class="mt-3 mb-2 fs-5 text-muted">No members found</p>
                        <p class="text-muted small mb-3">Start by adding your first member to the system</p>
                        <a href="{{ route('members.create') }}" class="btn btn-primary">
                            <i class="bi bi-person-plus me-1"></i> Add First Member
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <style>
        .sophisticated-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15) !important;
        }
        .sophisticated-card:hover img {
            transform: scale(1.05);
        }
    </style>

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
