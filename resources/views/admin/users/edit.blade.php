@extends('layouts.app')
@section('title', 'Edit User')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Edit User</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user) }}" method="POST" class="row g-3" enctype="multipart/form-data">
                @csrf @method('PUT')
                
                <div class="col-12">
                    <label class="form-label">Profile Photo</label>
                    <div class="d-flex align-items-center gap-3">
                        @if($user->profile_photo)
                            <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover; border: 2px solid #ddd;">
                        @else
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background: #e9ecef; border: 2px solid #ddd;">
                                <i class="bi bi-person-circle" style="font-size: 3rem; color: #6c757d;"></i>
                            </div>
                        @endif
                        <div class="flex-grow-1">
                            <input type="file" name="profile_photo" class="form-control @error('profile_photo') is-invalid @enderror" accept="image/*">
                            @error('profile_photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted">JPG, PNG, or GIF. Max 2MB.</small>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                        @foreach($roles as $role)
                            <option value="{{ $role }}" @selected(old('role', $user->role) === $role)>{{ ucfirst($role) }}</option>
                        @endforeach
                    </select>
                    @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="text-muted">Only Admins can change roles.</small>
                </div>
                <div class="col-md-6"></div>

                <div class="col-12">
                    <hr>
                    <h6 class="mb-3">Gym Assignments</h6>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 40%">Gym</th>
                                    <th style="width: 20%">Assigned</th>
                                    <th style="width: 25%">Role in Gym</th>
                                    <th style="width: 15%">Default</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($gyms as $gym)
                                    @php
                                        $isAssigned = array_key_exists($gym->id, $assigned);
                                        $pivotRole = $assigned[$gym->id] ?? $user->role;
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <strong>{{ $gym->name }}</strong>
                                                <small class="text-muted">{{ $gym->address }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input js-gym-assign" type="checkbox" name="gyms[]" value="{{ $gym->id }}" id="assign-{{ $gym->id }}" @checked($isAssigned)>
                                                <label class="form-check-label" for="assign-{{ $gym->id }}">Assigned</label>
                                            </div>
                                        </td>
                                        <td>
                                            <select name="gym_roles[{{ $gym->id }}]" class="form-select form-select-sm js-gym-role" @disabled(!$isAssigned)>
                                                @foreach($roles as $role)
                                                    <option value="{{ $role }}" @selected($pivotRole === $role)>{{ ucfirst($role) }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" class="form-check-input js-gym-default" name="default_gym_id" value="{{ $gym->id }}" @checked($user->default_gym_id == $gym->id) @disabled(!$isAssigned)>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <small class="text-muted d-block">Select the gyms this user can access and set their role per gym. Mark one as default for faster login context.</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label">New Password <span class="text-muted">(optional)</span></label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
                </div>
                <div class="col-12 d-flex justify-content-between">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back</a>
                    <div>
                        <form action="{{ route('admin.users.reset-password', $user) }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-outline-warning me-2" onclick="return confirm('Reset password for this user? A temporary password will be generated.')"><i class="bi bi-shield-lock"></i> Reset Password</button>
                        </form>
                        <button class="btn btn-primary"><i class="bi bi-save"></i> Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const rows = document.querySelectorAll('input.js-gym-assign');
    rows.forEach(function(cb) {
        cb.addEventListener('change', function() {
            const tr = cb.closest('tr');
            const roleSelect = tr.querySelector('select.js-gym-role');
            const defaultRadio = tr.querySelector('input.js-gym-default');
            if (cb.checked) {
                roleSelect.removeAttribute('disabled');
                defaultRadio.removeAttribute('disabled');
            } else {
                roleSelect.setAttribute('disabled', 'disabled');
                defaultRadio.checked = false;
                defaultRadio.setAttribute('disabled', 'disabled');
            }
        });
    });
});
</script>
@endpush
