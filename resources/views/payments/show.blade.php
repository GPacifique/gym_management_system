<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">Payment #{{ $payment->id }}</h2>
            <div class="d-flex gap-2">
                <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Payments
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
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light fw-semibold">Overview</div>
                        <div class="card-body">
                            <dl class="row mb-0">
                                <dt class="col-sm-3">Member</dt>
                                <dd class="col-sm-9">
                                    @php($member = $payment->member)
                                    @if($member)
                                        @php($displayName = trim(($member->first_name ?? '') . ' ' . ($member->last_name ?? '')) ?: ($member->name ?? $member->email ?? '—'))
                                        @hasAnyRole('admin','manager')
                                            <a href="{{ route('members.show', $member) }}" class="text-decoration-none">{{ $displayName }}</a>
                                        @else
                                            {{ $displayName }}
                                        @endhasAnyRole
                                        @if(!empty($member->email) && $displayName !== $member->email)
                                            <div class="text-muted small">{{ $member->email }}</div>
                                        @endif
                                    @else
                                        —
                                    @endif
                                </dd>

                                <dt class="col-sm-3">Subscription</dt>
                                <dd class="col-sm-9">
                                    @if($payment->subscription)
                                        <span class="badge bg-info">{{ $payment->subscription->plan_name }}</span>
                                        <span class="text-muted ms-2">${{ number_format($payment->subscription->price, 2) }}</span>
                                    @else
                                        <span class="badge bg-secondary">N/A</span>
                                    @endif
                                </dd>

                                <dt class="col-sm-3">Amount</dt>
                                <dd class="col-sm-9 fw-semibold text-success">${{ number_format($payment->amount, 2) }}</dd>

                                <dt class="col-sm-3">Method</dt>
                                <dd class="col-sm-9">
                                    <span class="badge bg-{{ $payment->method === 'cash' ? 'success' : ($payment->method === 'card' ? 'primary' : ($payment->method === 'bank_transfer' ? 'secondary' : ($payment->method === 'mobile_payment' ? 'info' : 'warning'))) }}">
                                        {{ ucfirst(str_replace('_', ' ', $payment->method)) }}
                                    </span>
                                </dd>

                                <dt class="col-sm-3">Payment Date</dt>
                                <dd class="col-sm-9">{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</dd>

                                @if(!empty($payment->transaction_id))
                                    <dt class="col-sm-3">Transaction ID</dt>
                                    <dd class="col-sm-9"><code>{{ $payment->transaction_id }}</code></dd>
                                @endif

                                <dt class="col-sm-3">Created</dt>
                                <dd class="col-sm-9">{{ $payment->created_at?->format('M d, Y H:i') }}</dd>

                                <dt class="col-sm-3">Updated</dt>
                                <dd class="col-sm-9">{{ $payment->updated_at?->format('M d, Y H:i') }}</dd>
                            </dl>
                        </div>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-header bg-light fw-semibold">Actions</div>
                        <div class="card-body">
                            <div class="d-flex flex-wrap gap-2">
                                @hasAnyRole('admin','manager')
                                    <a href="{{ route('payments.edit', $payment) }}" class="btn btn-outline-primary">
                                        <i class="bi bi-pencil"></i> Edit Payment
                                    </a>
                                @endhasAnyRole
                                <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-list"></i> Back to List
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
