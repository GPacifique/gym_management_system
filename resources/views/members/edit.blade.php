@extends('layouts.app')
@section('title', 'Edit Member')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0">Edit Member - {{ $member->full_name }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('members.update', $member) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                    <div class="col-md-6">
                        <label class="form-label">Photo</label>
                        <input type="file" name="photo" accept="image/*" class="form-control">
                        <div class="form-text">Leave empty to keep current photo.</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Current Photo</label>
                        <div>
                            <img src="{{ $member->photo_url }}" alt="{{ $member->name }}" class="img-thumbnail" style="max-height: 120px;">
                        </div>
                    </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">First Name</label>
                        <input type="text" name="first_name" value="{{ old('first_name', $member->first_name) }}" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $member->last_name) }}" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email', $member->email) }}" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $member->phone) }}" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Microchip ID</label>
                        <input type="text" name="chip_id" value="{{ old('chip_id', $member->chip_id) }}" class="form-control" placeholder="Scan tag or type manually">
                        <div class="form-text">Focus this field and scan with your reader. Leave empty to remove.</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-select">
                            <option value="">Select</option>
                            <option value="male" {{ $member->gender=='male'?'selected':'' }}>Male</option>
                            <option value="female" {{ $member->gender=='female'?'selected':'' }}>Female</option>
                            <option value="other" {{ $member->gender=='other'?'selected':'' }}>Other</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" name="dob" value="{{ old('dob', $member->dob) }}" class="form-control">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" value="{{ old('address', $member->address) }}" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Trainer</label>
                        <select name="trainer_id" class="form-select">
                            <option value="">None</option>
                            @foreach($trainers as $trainer)
                                <option value="{{ $trainer->id }}" {{ $member->trainer_id == $trainer->id ? 'selected' : '' }}>
                                    {{ $trainer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check2"></i> Update
                    </button>
                    <a href="{{ route('members.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
