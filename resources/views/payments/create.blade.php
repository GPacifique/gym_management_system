<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">Record Payment</h2>
            <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Payments
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <form action="{{ route('payments.store') }}" method="POST">
                                @csrf

                                <!-- Member Selection -->
                                <div class="mb-3">
                                    <label for="member_id" class="form-label">Member <span class="text-danger">*</span></label>
                                    <select class="form-select @error('member_id') is-invalid @enderror" id="member_id" name="member_id" required>
                                        <option value="">Select a member</option>
                                        @foreach($members as $member)
                                            @php
                                                $fullName = $member->name ?? trim(($member->first_name ?? '') . ' ' . ($member->last_name ?? ''));
                                            @endphp
                                            <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                                                {{ $fullName ?: 'Unknown Member' }} @if(!empty($member->email)) - {{ $member->email }} @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('member_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Subscription Selection (Optional) -->
                                <div class="mb-3">
                                    <label for="subscription_id" class="form-label">Subscription (Optional)</label>
                                    <select class="form-select @error('subscription_id') is-invalid @enderror" id="subscription_id" name="subscription_id">
                                        <option value="">No subscription</option>
                                        @foreach($subscriptions as $subscription)
                                            <option value="{{ $subscription->id }}" {{ old('subscription_id') == $subscription->id ? 'selected' : '' }}>
                                                {{ $subscription->plan_name }} - ${{ number_format($subscription->price, 2) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('subscription_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Link this payment to a subscription plan</div>
                                </div>

                                <!-- Amount -->
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" step="0.01" min="0" value="{{ old('amount') }}" required>
                                        @error('amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Payment Method -->
                                <div class="mb-3">
                                    <label for="method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                                    <select class="form-select @error('method') is-invalid @enderror" id="method" name="method" required>
                                        <option value="">Select payment method</option>
                                        <option value="cash" {{ old('method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="card" {{ old('method') == 'card' ? 'selected' : '' }}>Credit/Debit Card</option>
                                        <option value="bank_transfer" {{ old('method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                        <option value="mobile_payment" {{ old('method') == 'mobile_payment' ? 'selected' : '' }}>Mobile Payment</option>
                                        <option value="other" {{ old('method') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Payment Date -->
                                <div class="mb-3">
                                    <label for="payment_date" class="form-label">Payment Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('payment_date') is-invalid @enderror" id="payment_date" name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" required>
                                    @error('payment_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Submit Buttons -->
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle"></i> Record Payment
                                    </button>
                                    <a href="{{ route('payments.index') }}" class="btn btn-secondary">
                                        Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-fill amount when subscription is selected
        document.getElementById('subscription_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                const subscriptionText = selectedOption.text;
                const priceMatch = subscriptionText.match(/\$([0-9,.]+)/);
                if (priceMatch) {
                    document.getElementById('amount').value = priceMatch[1].replace(',', '');
                }
            }
        });
    </script>
</x-app-layout>
