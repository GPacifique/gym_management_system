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

            <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                <div class="card-body p-0">
                    <x-sophisticated-table :headers="['ID', 'Member', 'Subscription', 'Amount', 'Method', 'Payment Date', 'Actions']">
                        @forelse($payments as $payment)
                            <tr>
                                <td><span class="badge bg-light text-dark">#{{ $payment->id }}</span></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @php
                                            $member = $payment->member;
                                            $fullName = trim((($member?->first_name) ?? '') . ' ' . (($member?->last_name) ?? ''));
                                            if ($fullName === '' && ($member?->name ?? '') !== '') {
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
                                        <div class="avatar-circle me-3" style="width: 42px; height: 42px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.9rem;">
                                            {{ $initials !== '' ? $initials : '?' }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold text-dark">
                                                @if($member)
                                                    @hasAnyRole('admin','manager')
                                                        <a href="{{ route('members.show', $member) }}" class="text-decoration-none text-dark">
                                                            {{ $fullName !== '' ? $fullName : ($member->email ?? '—') }}
                                                        </a>
                                                    @else
                                                        {{ $fullName !== '' ? $fullName : ($member->email ?? '—') }}
                                                    @endhasAnyRole
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </div>
                                            @if(!empty($member?->email) && $fullName !== $member->email)
                                                <small class="text-muted"><i class="bi bi-envelope me-1"></i>{{ $member->email }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($payment->subscription)
                                        <span class="badge bg-info bg-gradient">
                                            <i class="bi bi-tag me-1"></i>{{ $payment->subscription->plan_name }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-50">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-bold text-success" style="font-size: 1.05rem;">
                                        <i class="bi bi-currency-dollar"></i>{{ number_format($payment->amount, 2) }}
                                    </span>
                                </td>
                                <td>
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
                                    <span class="badge bg-{{ $color }} bg-gradient">
                                        <i class="bi bi-{{ $icon }} me-1"></i>{{ ucfirst(str_replace('_', ' ', $payment->method)) }}
                                    </span>
                                </td>
                                <td>
                                    <i class="bi bi-calendar3 text-muted me-1"></i>
                                    <span>{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</span>
                                    <br>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($payment->payment_date)->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('payments.show', $payment) }}" class="btn btn-outline-primary" title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @hasAnyRole('admin','manager')
                                            <a href="{{ route('payments.edit', $payment) }}" class="btn btn-outline-warning" title="Edit Payment">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('payments.destroy', $payment) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this payment?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Delete Payment">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endhasAnyRole
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox" style="font-size: 4rem; opacity: 0.3;"></i>
                                        <p class="mt-3 mb-2 fs-5">No payments found</p>
                                        <p class="text-muted small mb-3">Start by recording your first payment</p>
                                        <a href="{{ route('payments.create') }}" class="btn btn-primary">
                                            <i class="bi bi-plus-circle me-1"></i> Record First Payment
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </x-sophisticated-table>

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
