@extends('layouts.app')
@section('title', 'Edit ' . $gym->name)

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="bi bi-pencil-square me-2"></i>Edit Gym Profile
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('gyms.update', $gym) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <!-- Logo Upload -->
                    <div class="col-12">
                        <label class="form-label fw-bold">Gym Logo</label>
                        <div class="d-flex align-items-center gap-3">
                            @if($gym->logo)
                                <img src="{{ asset('storage/' . $gym->logo) }}" alt="Current Logo" class="rounded" style="width: 100px; height: 100px; object-fit: cover; border: 2px solid #ddd;" id="currentLogo">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 100px; height: 100px; border: 2px solid #ddd;" id="currentLogo">
                                    <i class="bi bi-building" style="font-size: 2.5rem; color: #6c757d;"></i>
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/*" onchange="previewLogo(event)">
                                @error('logo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <small class="text-muted">JPG, PNG, or GIF. Max 2MB.</small>
                            </div>
                        </div>
                        <div id="logoPreview" class="mt-3" style="display: none;">
                            <p class="text-sm text-muted mb-2">Preview:</p>
                            <img id="previewImage" src="" alt="Preview" class="rounded" style="width: 120px; height: 120px; object-fit: cover; border: 2px solid #ddd;">
                        </div>
                    </div>

                    <!-- Basic Information -->
                    <div class="col-md-6">
                        <label for="name" class="form-label fw-bold">Gym Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $gym->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="phone" class="form-label fw-bold">Phone</label>
                        <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $gym->phone) }}">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label fw-bold">Email</label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $gym->email) }}">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="website" class="form-label fw-bold">Website</label>
                        <input type="url" name="website" id="website" class="form-control @error('website') is-invalid @enderror" value="{{ old('website', $gym->website) }}" placeholder="https://example.com">
                        @error('website')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label for="address" class="form-label fw-bold">Address</label>
                        <input type="text" name="address" id="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address', $gym->address) }}">
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="timezone" class="form-label fw-bold">Timezone</label>
                        <select name="timezone" id="timezone" class="form-select @error('timezone') is-invalid @enderror">
                            <option value="">Select Timezone</option>
                            @foreach(timezone_identifiers_list() as $tz)
                                <option value="{{ $tz }}" @selected(old('timezone', $gym->timezone) === $tz)>{{ $tz }}</option>
                            @endforeach
                        </select>
                        @error('timezone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label for="description" class="form-label fw-bold">Description</label>
                        <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror" placeholder="Brief description of your gym">{{ old('description', $gym->description) }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="text-muted">Maximum 1000 characters</small>
                    </div>

                    <div class="col-12">
                        <label for="opening_hours" class="form-label fw-bold">Opening Hours</label>
                        <textarea name="opening_hours" id="opening_hours" rows="5" class="form-control @error('opening_hours') is-invalid @enderror" placeholder="Monday - Friday: 6:00 AM - 10:00 PM&#10;Saturday: 8:00 AM - 8:00 PM&#10;Sunday: 9:00 AM - 6:00 PM">{{ old('opening_hours', $gym->opening_hours) }}</textarea>
                        @error('opening_hours')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="text-muted">Enter operating hours (one per line)</small>
                    </div>

                    <div class="col-12">
                        <hr>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('gyms.show', $gym) }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>Save Changes
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewLogo(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImage').src = e.target.result;
            document.getElementById('logoPreview').style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        document.getElementById('logoPreview').style.display = 'none';
    }
}
</script>
@endsection
