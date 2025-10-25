@extends('layouts.app')
@section('title', 'Edit Trainer')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0">Edit Trainer - {{ $trainer->name }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('trainers.update', $trainer) }}" method="POST">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" value="{{ old('name', $trainer->name) }}" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email', $trainer->email) }}" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $trainer->phone) }}" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Specialization</label>
                        <input type="text" name="specialization" value="{{ old('specialization', $trainer->specialization) }}" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Salary ($)</label>
                        <input type="number" name="salary" value="{{ old('salary', $trainer->salary) }}" class="form-control" step="0.01">
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check2"></i> Update
                    </button>
                    <a href="{{ route('trainers.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
