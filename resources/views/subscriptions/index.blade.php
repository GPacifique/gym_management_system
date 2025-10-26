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

    <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
        <div class="card-body p-0">
            <x-sophisticated-table :headers="['#', 'Member', 'Plan', 'Price', 'Start Date', 'End Date', 'Status', 'Actions']">
                @forelse ($subscriptions as $subscription)
                    <tr class="{{ $subscription->status === 'expired' ? 'table-light opacity-75' : '' }}">
                        <td><span class="badge bg-light text-dark">#{{ $loop->iteration }}</span></td>
                        <td>
                            <div class="d-flex align-items-center">
                                @php
                                    $member = $subscription->member;
                                    $fullName = trim((($member?->first_name) ?? '') . ' ' . (($member?->last_name) ?? ''));
                                    $initials = '';
                                    if ($fullName !== '' && $fullName !== ' ') {
                                        $parts = preg_split('/\s+/', trim($fullName));
                                        if (!empty($parts[0])) { $initials .= mb_strtoupper(mb_substr($parts[0], 0, 1)); }
                                        if (!empty($parts[1])) { $initials .= mb_strtoupper(mb_substr($parts[1], 0, 1)); }
                                    }
                                @endphp
                                <div class="avatar-circle me-3" style="width: 38px; height: 38px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.85rem;">
                                    {{ $initials !== '' ? $initials : '?' }}
                                </div>
                                <div>
                                    <div class="fw-semibold text-dark">{{ $fullName !== '' && $fullName !== ' ' ? $fullName : 'N/A' }}</div>
                                    @if(!empty($member?->email))
                                        <small class="text-muted"><i class="bi bi-envelope me-1"></i>{{ $member->email }}</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-primary bg-gradient">
                                <i class="bi bi-star-fill me-1"></i>{{ $subscription->plan_name }}
                            </span>
                        </td>
                        <td>
                            <span class="fw-bold text-success" style="font-size: 1.05rem;">
                                RWF {{ number_format($subscription->price, 2) }}
                            </span>
                        </td>
                        <td>
                            <i class="bi bi-calendar-check text-success me-1"></i>
                            <span>{{ \Carbon\Carbon::parse($subscription->start_date)->format('M d, Y') }}</span>
                        </td>
                        <td>
                            <i class="bi bi-calendar-x text-danger me-1"></i>
                            <span>{{ \Carbon\Carbon::parse($subscription->end_date)->format('M d, Y') }}</span>
                            <br>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($subscription->end_date)->diffForHumans() }}</small>
                        </td>
                        <td>
                            @if($subscription->status == 'active')
                                <span class="badge bg-success bg-gradient" style="font-size: 0.85rem;">
                                    <i class="bi bi-check-circle me-1"></i>Active
                                </span>
                            @elseif($subscription->status == 'expired')
                                <span class="badge bg-danger bg-gradient" style="font-size: 0.85rem;">
                                    <i class="bi bi-x-circle me-1"></i>Expired
                                </span>
                            @else
                                <span class="badge bg-secondary bg-gradient" style="font-size: 0.85rem;">{{ ucfirst($subscription->status) }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('subscriptions.show', $subscription) }}" class="btn btn-outline-info" title="View Details">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('subscriptions.edit', $subscription) }}" class="btn btn-outline-warning" title="Edit Subscription">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('subscriptions.destroy', $subscription) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this subscription?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Delete Subscription">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-card-list" style="font-size: 4rem; opacity: 0.3;"></i>
                                <p class="mt-3 mb-2 fs-5">No subscriptions found</p>
                                <p class="text-muted small mb-3">Start by creating your first subscription plan</p>
                                <a href="{{ route('subscriptions.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i> Create First Subscription
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </x-sophisticated-table>
        </div>
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
