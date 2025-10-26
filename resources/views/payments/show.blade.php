<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">
                <i class="bi bi-receipt text-primary me-2"></i>Payment Details
            </h2>
            <div class="d-flex gap-2">
                <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
                @hasAnyRole('admin','manager')
                    <a href="{{ route('payments.edit', $payment) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <form action="{{ route('payments.destroy', $payment) }}" method="POST" onsubmit="return confirm('Delete this payment?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </form>
                @endhasAnyRole
            </div>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <!-- Payment Header -->
                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
                        <div class="card-header border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 2rem;">
                            <div class="text-white text-center">
                                <div class="mb-3">
                                    <i class="bi bi-check-circle-fill" style="font-size: 3rem;"></i>
                                </div>
                                <h3 class="mb-1 fw-bold">Payment #{{ $payment->id }}</h3>
                                <p class="mb-0 opacity-75">Transaction recorded successfully</p>
                            </div>
                        </div>
                    </div>

                    <!-- Amount Card -->
                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
                        <div class="card-body text-center" style="padding: 2rem;">
                            <small class="text-muted text-uppercase d-block mb-2">Amount Paid</small>
                            <h1 class="display-4 fw-bold text-success mb-0">
                                <i class="bi bi-currency-dollar"></i>{{ number_format($payment->amount, 2) }}
                            </h1>
                        </div>
                    </div>

                    <!-- Payment Details -->
                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
                        <div class="card-header bg-white border-0 pb-0" style="padding: 1.5rem 1.5rem 1rem;">
                            <h5 class="mb-0 fw-bold">
                                <i class="bi bi-info-circle text-primary me-2"></i>Payment Information
                            </h5>
                        </div>
                        <div class="card-body" style="padding: 1.5rem;">
                            <div class="row g-3">
                                <!-- Member Info -->
                                <div class="col-12">
                                    <div class="p-3 rounded" style="background-color: #f8f9fa;">
                                        <small class="text-muted d-block mb-2">Member</small>
                                        @php($member = $payment->member)
                                        @if($member)
                                            @php($displayName = trim(($member->first_name ?? '') . ' ' . ($member->last_name ?? '')) ?: ($member->name ?? $member->email ?? '—'))
                                            <div class="d-flex align-items-center">
                                                @php
                                                    $initials = '';
                                                    if ($displayName !== '—') {
                                                        $parts = preg_split('/\s+/', trim($displayName));
                                                        if (!empty($parts[0])) { $initials .= mb_strtoupper(mb_substr($parts[0], 0, 1)); }
                                                        if (!empty($parts[1])) { $initials .= mb_strtoupper(mb_substr($parts[1], 0, 1)); }
                                                    }
                                                @endphp
                                                <div class="avatar-circle me-3" style="width: 42px; height: 42px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items-center; justify-content: center; color: white; font-weight: 600; font-size: 0.9rem;">
                                                    {{ $initials !== '' ? $initials : '?' }}
                                                </div>
                                                <div>
                                                    @hasAnyRole('admin','manager')
                                                        <a href="{{ route('members.show', $member) }}" class="text-decoration-none fw-bold text-dark">{{ $displayName }}</a>
                                                    @else
                                                        <span class="fw-bold">{{ $displayName }}</span>
                                                    @endhasAnyRole
                                                    @if(!empty($member->email) && $displayName !== $member->email)
                                                        <div class="text-muted small">
                                                            <i class="bi bi-envelope me-1"></i>{{ $member->email }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">Not specified</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Subscription -->
                                <div class="col-md-6">
                                    <div class="p-3 rounded" style="background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);">
                                        <small class="text-muted d-block mb-2">Subscription Plan</small>
                                        @if($payment->subscription)
                                            <span class="badge bg-info bg-gradient" style="font-size: 0.9rem;">
                                                <i class="bi bi-tag me-1"></i>{{ $payment->subscription->plan_name }}
                                            </span>
                                            <div class="text-muted small mt-1">RWF {{ number_format($payment->subscription->price, 2) }}</div>
                                        @else
                                            <span class="badge bg-secondary">N/A</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Payment Method -->
                                <div class="col-md-6">
                                    <div class="p-3 rounded" style="background: linear-gradient(135deg, #f093fb15 0%, #f5576c15 100%);">
                                        <small class="text-muted d-block mb-2">Payment Method</small>
                                        @php
                                            $methodColors = [
                                                'cash' => 'success',
                                                'card' => 'primary',
                                                'bank_transfer' => 'info',
                                                'mobile_payment' => 'warning',
                                                'other' => 'secondary'
                                            ];
                                            $methodIcons = [
                                                'cash' => 'cash-stack',
                                                'card' => 'credit-card',
                                                'bank_transfer' => 'bank',
                                                'mobile_payment' => 'phone',
                                                'other' => 'wallet2'
                                            ];
                                            $color = $methodColors[$payment->method] ?? 'secondary';
                                            $icon = $methodIcons[$payment->method] ?? 'wallet2';
                                        @endphp
                                        <span class="badge bg-{{ $color }} bg-gradient" style="font-size: 0.9rem;">
                                            <i class="bi bi-{{ $icon }} me-1"></i>{{ ucfirst(str_replace('_', ' ', $payment->method)) }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Payment Date -->
                                <div class="col-md-6">
                                    <div class="p-3 rounded" style="background-color: #f8f9fa;">
                                        <small class="text-muted d-block mb-2">Payment Date</small>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-calendar-event text-primary me-2 fs-5"></i>
                                            <span class="fw-semibold">{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</span>
                                        </div>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($payment->payment_date)->diffForHumans() }}</small>
                                    </div>
                                </div>

                                <!-- Transaction ID -->
                                @if(!empty($payment->transaction_id))
                                <div class="col-md-6">
                                    <div class="p-3 rounded" style="background-color: #f8f9fa;">
                                        <small class="text-muted d-block mb-2">Transaction ID</small>
                                        <code style="font-size: 0.9rem;">{{ $payment->transaction_id }}</code>
                                    </div>
                                </div>
                                @endif

                                <!-- Timestamps -->
                                <div class="col-12">
                                    <div class="p-3 rounded" style="background-color: #f8f9fa;">
                                        <div class="row">
                                            <div class="col-6">
                                                <small class="text-muted d-block mb-1">Created At</small>
                                                <small class="fw-semibold">{{ $payment->created_at?->format('M d, Y H:i') }}</small>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted d-block mb-1">Last Updated</small>
                                                <small class="fw-semibold">{{ $payment->updated_at?->format('M d, Y H:i') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
