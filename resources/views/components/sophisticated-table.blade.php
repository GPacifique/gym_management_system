@props(['headers' => [], 'striped' => true, 'hoverable' => true])

<div class="table-responsive">
    <table class="table sophisticated-table {{ $striped ? 'table-striped' : '' }} {{ $hoverable ? 'table-hover' : '' }} align-middle mb-0">
        @if(!empty($headers))
        <thead class="table-header-sophisticated">
            <tr>
                @foreach($headers as $header)
                    <th scope="col" class="text-uppercase fw-semibold text-muted small">{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        @endif
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>

<style>
.sophisticated-table {
    border-collapse: separate;
    border-spacing: 0;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    border-radius: 12px;
    overflow: hidden;
}

.table-header-sophisticated {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white !important;
    border: none;
}

.table-header-sophisticated th {
    border: none;
    padding: 1rem 1.25rem;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    font-weight: 600;
    color: white !important;
}

.sophisticated-table tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid #f0f0f0;
}

.sophisticated-table tbody tr:last-child {
    border-bottom: none;
}

.sophisticated-table tbody tr:hover {
    background-color: #f8f9fa;
    transform: scale(1.01);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    position: relative;
    z-index: 1;
}

.sophisticated-table tbody td {
    padding: 1rem 1.25rem;
    vertical-align: middle;
    border: none;
}

.sophisticated-table tbody td:first-child {
    font-weight: 600;
    color: #2d3748;
}

/* Badge improvements */
.sophisticated-table .badge {
    padding: 0.4em 0.8em;
    font-weight: 500;
    font-size: 0.75rem;
    border-radius: 6px;
    letter-spacing: 0.3px;
}

/* Action buttons */
.sophisticated-table .btn-sm {
    padding: 0.35rem 0.75rem;
    font-size: 0.8rem;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.sophisticated-table .btn-sm:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

/* Status indicators */
.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    display: inline-block;
    margin-right: 6px;
    animation: pulse 2s infinite;
}

.status-dot.active {
    background-color: #10b981;
}

.status-dot.inactive {
    background-color: #ef4444;
}

.status-dot.pending {
    background-color: #f59e0b;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

/* Responsive */
@media (max-width: 768px) {
    .sophisticated-table tbody td,
    .sophisticated-table thead th {
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
    }
}
</style>
