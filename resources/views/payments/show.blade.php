<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0"><i class="bi bi-receipt text-primary me-2"></i>Payment Details</h2>
            <div>
                <a href="{{ Route::has('payments.index') ? route('payments.index') : url()->previous() }}" class="btn btn-outline-secondary">Back</a>
            </div>
        </div>
    </x-slot>

    <div class="py-4 container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <h3>Payment #{{ $payment->id }}</h3>
                        <p class="text-muted">{{ $payment->method ? ucfirst(str_replace('_',' ', $payment->method)) : 'Method' }} • {{ $payment->currency ?? '' }} {{ number_format($payment->amount, 2) }}</p>

                        <dl class="row">
                            <dt class="col-sm-4">Member</dt>
                            <dd class="col-sm-8">{{ optional($payment->member)->name ?? '—' }}</dd>

                            <dt class="col-sm-4">Subscription</dt>
                            <dd class="col-sm-8">{{ optional($payment->subscription)->plan_name ?? 'N/A' }}</dd>

                            <dt class="col-sm-4">Payment Date</dt>
                            <dd class="col-sm-8">{{ optional($payment->payment_date) ? \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') : '—' }}</dd>

                            <dt class="col-sm-4">Transaction ID</dt>
                            <dd class="col-sm-8">{{ $payment->transaction_id ?? '—' }}</dd>

                            <dt class="col-sm-4">Created At</dt>
                            <dd class="col-sm-8">{{ $payment->created_at?->format('M d, Y H:i') ?? '—' }}</dd>

                            <dt class="col-sm-4">Last Updated</dt>
                            <dd class="col-sm-8">{{ $payment->updated_at?->format('M d, Y H:i') ?? '—' }}</dd>
                        </dl>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
