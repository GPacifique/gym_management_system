@extends('layouts.app')
@section('title', 'Scan Check-in')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-upc-scan"></i> Scan to Check-in / Check-out</h5>
                    <a href="{{ route('attendances.index') }}" class="btn btn-outline-light btn-sm">Back</a>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <p class="text-muted">Connect your microchip/RFID reader (keyboard-wedge). Focus the input below and scan the tag. Most readers send an Enter key at the end to submit.</p>
                    <form action="{{ route('attendances.scan.check') }}" method="POST" id="scan-form">
                        @csrf
                        <div class="input-group input-group-lg">
                            <span class="input-group-text"><i class="bi bi-broadcast-pin"></i></span>
                            <input type="text" name="chip_id" id="chip_id" class="form-control" placeholder="Scan tag..." autocomplete="off" autofocus>
                            <button class="btn btn-primary" type="submit"><i class="bi bi-send"></i> Submit</button>
                        </div>
                        <div class="form-text">Tip: If your reader doesn't send Enter, press Enter manually or click Submit.</div>
                    </form>
                </div>
            </div>

            <div class="card mt-3 border-0">
                <div class="card-body">
                    <h6 class="fw-bold">How it works</h6>
                    <ul class="mb-0">
                        <li>If the member has no active visit, we create a check-in.</li>
                        <li>If the member is already checked in, we record a check-out.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function(){
        const input = document.getElementById('chip_id');
        const form = document.getElementById('scan-form');
        // If scanner sends Enter, the form will submit naturally.
        // As a fallback, auto-submit after brief inactivity.
        let timer;
        input.addEventListener('input', () => {
            if (timer) clearTimeout(timer);
            timer = setTimeout(() => {
                if (input.value && input.value.length >= 4) {
                    form.submit();
                }
            }, 700);
        });
        // Keep focus on the input for faster scans
        window.addEventListener('load', () => input.focus());
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                input.value = '';
                input.focus();
            }
        });
    })();
</script>
@endsection
