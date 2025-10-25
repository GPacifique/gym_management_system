<x-app-layout>
    <!-- Debug marker: remove after verification -->
    <div class="container-fluid mt-2">
        <div class="alert alert-secondary py-2" role="alert">
            <strong>Admin Dashboard</strong> — view loaded successfully
        </div>
    </div>
    <div class="container-fluid mt-4">
        <x-dashboard.header 
            :last-update="$lastUpdate ?? null"
            :is-stale="$isStale ?? false"
            :errors="$errors ?? []"
        />

        <x-dashboard.stats-cards 
            :member-count="$memberCount ?? 0"
            :trainer-count="$trainerCount ?? 0"
            :active-subscriptions="$activeSubscriptions ?? 0"
            :total-revenue="$totalRevenue ?? 0"
        />

        <div class="row mt-3">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted">Today's Revenue</div>
                            <div class="h4 mb-0">$ {{ number_format($todayRevenue ?? 0, 2) }}</div>
                        </div>
                        <div class="text-success display-6"><i class="bi bi-cash-stack"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted">Today's Check-ins</div>
                            <div class="h4 mb-0">{{ $todayCheckIns ?? 0 }}</div>
                        </div>
                        <div class="text-primary display-6"><i class="bi bi-people"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header">Membership Growth</div>
                    <div class="card-body">
                        <canvas id="membersChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header">Monthly Revenue</div>
                    <div class="card-body">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const mctx = document.getElementById('membersChart');
        const rctx = document.getElementById('revenueChart');
        if (mctx) new Chart(mctx, {
            type: 'line',
            data: { labels: @json($chartLabels ?? []), datasets: [{ label: 'New Members', data: @json($membersChartData ?? []), borderColor: '#0d6efd', tension: 0.3 }] }
        });
        if (rctx) new Chart(rctx, {
            type: 'bar',
            data: { labels: @json($chartLabels ?? []), datasets: [{ label: 'Revenue ($)', data: @json($revenueChartData ?? []), backgroundColor: '#dc3545' }] }
        });

        // Refresh support (per-role)
        const refreshBtn = document.getElementById('refreshDashboard');
        if (refreshBtn && mctx && rctx) {
            const membersChart = Chart.getChart(mctx);
            const revenueChart = Chart.getChart(rctx);
            refreshBtn.addEventListener('click', async function(){
                const btn = this; const icon = btn.querySelector('i'); btn.disabled = true; if(icon) icon.classList.add('spin');
                try {
                    const res = await fetch('{{ route('dashboard.refresh') }}', { headers: { 'Accept': 'application/json' } });
                    const data = await res.json();
                    membersChart.data.labels = data.chart.labels || [];
                    membersChart.data.datasets[0].data = data.chart.members || [];
                    membersChart.update();
                    revenueChart.data.labels = data.chart.labels || [];
                    revenueChart.data.datasets[0].data = data.chart.revenue || [];
                    revenueChart.update();
                } catch(e) { window.location.reload(); }
                finally { btn.disabled = false; if(icon) icon.classList.remove('spin'); }
            });
        }
    </script>
</x-app-layout>
