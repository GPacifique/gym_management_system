<x-app-layout>
    <div class="container-fluid mt-4">
        @php($__gym = \App\Models\Gym::find(session('gym_id') ?? Auth::user()->default_gym_id))
        <div class="row mb-3">
            <div class="col-12">
                <h3 class="fw-bold mb-0">
                    <i class="bi bi-building me-2"></i>{{ $__gym?->name ?? 'Select Gym' }}
                    <small class="text-muted ms-2">Dashboard</small>
                </h3>
            </div>
        </div>

        <x-dashboard.header 
            :last-update="$lastUpdate ?? null"
            :is-stale="$isStale ?? false"
            :errors="$errors ?? []"
        />

        <div class="row g-3">
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted">Today's Revenue</div>
                            <div class="h4 mb-0">RWF {{ number_format($todayRevenue ?? 0, 2) }}</div>
                        </div>
                        <div class="text-success display-6"><i class="bi bi-cash-coin"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted">Today's Check-ins</div>
                            <div class="h4 mb-0">{{ $todayCheckIns ?? 0 }}</div>
                        </div>
                        <div class="text-primary display-6"><i class="bi bi-person-check"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted">Total Revenue</div>
                            <div class="h4 mb-0">RWF {{ number_format($totalRevenue ?? 0, 2) }}</div>
                        </div>
                        <div class="text-danger display-6"><i class="bi bi-wallet2"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header">Monthly Revenue</div>
                    <div class="card-body">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header">Quick Actions</div>
                    <div class="card-body d-grid gap-2">
                        <a href="{{ route('payments.create') }}" class="btn btn-outline-success">
                            <i class="bi bi-cash"></i> Record Payment
                        </a>
                        <a href="{{ route('attendances.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-person-check"></i> Record Attendance
                        </a>
                        <a href="{{ route('attendances.export') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-download"></i> Export Attendance
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const rctx = document.getElementById('revenueChart');
        if (rctx) new Chart(rctx, {
            type: 'bar',
            data: { labels: @json($chartLabels ?? []), datasets: [{ label: 'Revenue (RWF)', data: @json($revenueChartData ?? []), backgroundColor: '#0dcaf0' }] }
        });

        // Refresh support (per-role)
        const refreshBtn = document.getElementById('refreshDashboard');
        if (refreshBtn && rctx) {
            const revenueChart = Chart.getChart(rctx);
            refreshBtn.addEventListener('click', async function(){
                const btn = this; const icon = btn.querySelector('i'); btn.disabled = true; if(icon) icon.classList.add('spin');
                try {
                    const res = await fetch('{{ route('dashboard.refresh') }}', { headers: { 'Accept': 'application/json' } });
                    const data = await res.json();
                    revenueChart.data.labels = data.chart.labels || [];
                    revenueChart.data.datasets[0].data = data.chart.revenue || [];
                    revenueChart.update();
                } catch(e) { window.location.reload(); }
                finally { btn.disabled = false; if(icon) icon.classList.remove('spin'); }
            });
        }
    </script>
</x-app-layout>
