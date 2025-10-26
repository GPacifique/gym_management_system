@extends('layouts.app')
@section('title', 'User Management')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Users</h4>
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.users.create') }}" class="btn btn-success me-2">
                <i class="bi bi-person-plus"></i> Create User
            </a>
        <form method="GET" class="d-flex" action="{{ route('admin.users.index') }}">
            <input type="text" name="q" value="{{ request('q') }}" class="form-control me-2" placeholder="Search name/email/role" />
            <button class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
        </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
        <x-sophisticated-table :headers="['#', 'Profile', 'User Information', 'Role', 'Joined', 'Actions']">
            @forelse($users as $user)
                <tr>
                    <td><span class="badge bg-light text-dark">#{{ $user->id }}</span></td>
                    <td>
                        @if($user->profile_photo)
                            <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile" class="rounded-circle shadow-sm" style="width: 50px; height: 50px; object-fit: cover; border: 2px solid #f8f9fa;">
                        @else
                            @php
                                $name = $user->name ?? '';
                                $initials = '';
                                if ($name !== '') {
                                    $parts = preg_split('/\s+/', trim($name));
                                    if (!empty($parts[0])) { $initials .= mb_strtoupper(mb_substr($parts[0], 0, 1)); }
                                    if (!empty($parts[1])) { $initials .= mb_strtoupper(mb_substr($parts[1], 0, 1)); }
                                }
                            @endphp
                            <div class="rounded-circle shadow-sm d-inline-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; font-weight: 600; font-size: 1rem;">
                                {{ $initials !== '' ? $initials : '?' }}
                            </div>
                        @endif
                    </td>
                    <td>
                        <div>
                            <div class="fw-bold text-dark" style="font-size: 1.05rem;">{{ $user->name }}</div>
                            <small class="text-muted">
                                <i class="bi bi-envelope me-1"></i>{{ $user->email }}
                            </small>
                        </div>
                    </td>
                    <td>
                        <x-role-badge :role="$user->role" />
                    </td>
                    <td>
                        <div>
                            <i class="bi bi-calendar-check text-primary me-1"></i>
                            <span>{{ optional($user->created_at)->format('M d, Y') }}</span>
                        </div>
                        <small class="text-muted">{{ optional($user->created_at)->diffForHumans() }}</small>
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-warning" title="Edit User">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            @if(auth()->id() !== $user->id)
                            <form action="{{ route('admin.users.reset-password', $user) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-outline-info" onclick="return confirm('Reset password for {{ $user->name }}? A temporary password will be generated.')" title="Reset Password">
                                    <i class="bi bi-shield-lock"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger" onclick="return confirm('Delete {{ $user->name }}?')" title="Delete User">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="text-muted">
                            <i class="bi bi-people" style="font-size: 4rem; opacity: 0.3;"></i>
                            <p class="mt-3 mb-2 fs-5">No users found</p>
                            <p class="text-muted small mb-3">Start by creating your first user</p>
                            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                                <i class="bi bi-person-plus me-1"></i> Create First User
                            </a>
                        </div>
                    </td>
                </tr>
            @endforelse
        </x-sophisticated-table>
        <div class="card-footer bg-white border-0 px-3">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
