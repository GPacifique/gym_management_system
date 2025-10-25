<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
            <h2 class="h4 mb-0">Payments</h2>
            <div class="d-flex gap-2">
                <form action="{{ route('payments.index') }}" method="GET" class="d-flex">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control me-2" placeholder="Search member, method, plan, date..." />
                    <button class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
                </form>
                <a href="{{ route('payments.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Record Payment
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Member</th>
                                    <th>Subscription</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Payment Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments as $payment)
                                    <tr>
                                        <td>{{ $payment->id }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @php
                                                    $member = $payment->member;
                                                    $fullName = trim((($member?->first_name) ?? '') . ' ' . (($member?->last_name) ?? ''));
                                                    if ($fullName === '' && ($member?->name ?? '') !== '') {
                                                        // fallback to accessor if present
                                                        $fullName = $member?->name;
                                                    }
                                                    $initials = '';
                                                    if ($fullName !== '') {
                                                        $parts = preg_split('/\s+/', trim($fullName));
                                                        if (!empty($parts[0])) { $initials .= mb_strtoupper(mb_substr($parts[0], 0, 1)); }
                                                        if (!empty($parts[1])) { $initials .= mb_strtoupper(mb_substr($parts[1], 0, 1)); }
                                                    } elseif (!empty($member?->email)) {
                                                        $initials = mb_strtoupper(mb_substr($member->email, 0, 1));
                                                    }
                                                @endphp
                                                <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                                    {{ $initials !== '' ? $initials : '—' }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold">
                                                        @if($member)
                                                            @hasAnyRole('admin','manager')
                                                                <a href="{{ route('members.show', $member) }}" class="text-decoration-none">
                                                                    {{ $fullName !== '' ? $fullName : ($member->email ?? '—') }}
                                                                </a>
                                                            @else
                                                                {{ $fullName !== '' ? $fullName : ($member->email ?? '—') }}
                                                            @endhasAnyRole
                                                        @else
                                                            —
                                                        @endif
                                                    </div>
                                                    @if(!empty($member?->email) && $fullName !== $member->email)
                                                        <small class="text-muted">{{ $member->email }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($payment->subscription)
                                                <span class="badge bg-info">{{ $payment->subscription->plan_name }}</span>
                                            @else
                                                <span class="badge bg-secondary">N/A</span>
                                            @endif
                                        </td>
                                        <td class="fw-bold text-success">${{ number_format($payment->amount, 2) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $payment->method === 'cash' ? 'success' : ($payment->method === 'card' ? 'primary' : 'warning') }}">
                                                {{ ucfirst($payment->method) }}
                                            </span>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('payments.show', $payment) }}" class="btn btn-outline-primary" title="View">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                @hasAnyRole('admin','manager')
                                                    <a href="{{ route('payments.edit', $payment) }}" class="btn btn-outline-warning" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form action="{{ route('payments.destroy', $payment) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this payment?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @endhasAnyRole
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                                <p class="mt-2">No payments found.</p>
                                                <a href="{{ route('payments.create') }}" class="btn btn-primary btn-sm">
                                                    <i class="bi bi-plus-circle"></i> Record First Payment
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3 d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            @if($payments->total() > 0)
                                Showing {{ $payments->firstItem() }}–{{ $payments->lastItem() }} of {{ $payments->total() }}
                                @if(request('q')) for "{{ request('q') }}" @endif
                            @else
                                No results @if(request('q')) for "{{ request('q') }}" @endif
                            @endif
                        </div>
                        {{ $payments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .avatar {
            font-size: 0.875rem;
            font-weight: 600;
        }
        
        .table > :not(caption) > * > * {
            padding: 1rem 0.75rem;
        }
        
        .btn-group-sm .btn {
            padding: 0.25rem 0.5rem;
        }
    </style>
</x-app-layout>
