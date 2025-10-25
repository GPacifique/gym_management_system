@extends('layouts.app')
@section('title', 'Subscription Details')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white">
            <h4 class="mb-0">📄 Subscription Details</h4>
        </div>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-6"><strong>Member:</strong> {{ $subscription->member->full_name ?? 'N/A' }}</div>
                <div class="col-md-6"><strong>Plan Name:</strong> {{ $subscription->plan_name }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4"><strong>Price:</strong> ${{ number_format($subscription->price, 2) }}</div>
                <div class="col-md-4"><strong>Start Date:</strong> {{ $subscription->start_date->format('d M Y') }}</div>
                <div class="col-md-4"><strong>End Date:</strong> {{ $subscription->end_date->format('d M Y') }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4">
                    <strong>Status:</strong>
                    @if($subscription->status == 'active')
                        <span class="badge bg-success">Active</span>
                    @elseif($subscription->status == 'expired')
                        <span class="badge bg-danger">Expired</span>
                    @else
                        <span class="badge bg-secondary">{{ ucfirst($subscription->status) }}</span>
                    @endif
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('subscriptions.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
                <a href="{{ route('subscriptions.edit', $subscription) }}" class="btn btn-warning text-dark">
                    <i class="bi bi-pencil-square"></i> Edit
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
