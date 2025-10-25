@extends('layouts.app')
@section('title', 'Subscriptions')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold">💳 Subscriptions</h2>
        <a href="{{ route('subscriptions.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add Subscription
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
                    <th>Member</th>
                    <th>Plan</th>
                    <th>Price</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($subscriptions as $subscription)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $subscription->member->first_name ?? 'N/A' }}</td>
                        <td>{{ $subscription->plan_name }}</td>
                        <td>Rwf  {{ number_format($subscription->price, 2) }}</td>
                        <td>{{ \Carbon\Carbon::parse($subscription->start_date)->format('d M Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($subscription->end_date)->format('d M Y') }}</td>
                        <td>
                            @if($subscription->status == 'active')
                                <span class="badge bg-success">Active</span>
                            @elseif($subscription->status == 'expired')
                                <span class="badge bg-danger">Expired</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($subscription->status) }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('subscriptions.show', $subscription) }}" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('subscriptions.edit', $subscription) }}" class="btn btn-sm btn-outline-success">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('subscriptions.destroy', $subscription) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this subscription?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-3">No subscriptions found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-3">
        {{ $subscriptions->links() }}
    </div>

    <!-- Back button -->
    <div class="mt-4">
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
    </div>
</div>
@endsection
