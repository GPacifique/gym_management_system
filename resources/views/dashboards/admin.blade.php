<x-app-layout>
    <div class="container-fluid mt-4">

        {{-- Header --}}
        <x-dashboard.header 
            :last-update="$lastUpdate ?? null"
            :is-stale="$isStale ?? false"
            :errors="$errors ?? []"
        />

        {{-- Gym Title --}}
        <div class="row mb-3">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h3 class="fw-bold mb-0">
                    <i class="bi bi-building me-2"></i>{{ $gym?->name ?? 'Select Gym' }}
                    <small class="text-muted ms-2">Dashboard</small>
                </h3>

                <span class="badge bg-primary">
                    {{ ucfirst($role ?? 'admin') }}
                </span>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="row g-3">

            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted">Members</div>
                            <div class="h4 mb-0" id="memberCount">{{ $memberCount ?? 0 }}</div>
                        </div>
                        <i class="bi bi-people text-primary fs-2"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted">Trainers</div>
                            <div class="h4 mb-0">{{ $trainerCount ?? 0 }}</div>
                        </div>
                        <i class="bi bi-person-workspace text-success fs-2"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted">Active Subscriptions</div>
                            <div class="h4 mb-0">{{ $activeSubscriptions ?? 0 }}</div>
                        </div>
                        <i class="bi bi-calendar-check text-warning fs-2"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted">Total Revenue</div>
                            <div class="h4 mb-0" id="totalRevenue">{{ format_currency($totalRevenue ?? 0) }}</div>
                        </div>
                        <i class="bi bi-cash-stack text-danger fs-2"></i>
                    </div>
                </div>
            </div>

        </div>

        {{-- Today's Stats --}}
        <div class="row mt-3">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted">Today's Revenue</div>
                            <div class="h4 mb-0" id="todayRevenue">
                                {{ format_currency($todayRevenue ?? 0) }}
                            </div>
                        </div>
                        <i class="bi bi-cash text-success fs-2"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted">Today's Check-ins</div>
                            <div class="h4 mb-0" id="todayCheckIns">{{ $todayCheckIns ?? 0 }}</div>
                        </div>
                        <i class="bi bi-people text-primary fs-2"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts --}}
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

        {{-- Last Update --}}
        <div class="mt-3 text-end">
            <small class="text-muted">
                Updated: {{ $lastUpdate ?? 'N/A' }}
                @if($isStale)
                    <span class="text-warning">(cached)</span>
                @endif
            </small>
        </div>

    </div>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        let membersChartInstance;
        let revenueChartInstance;

        const mctx = document.getElementById('membersChart');
        const rctx = document.getElementById('revenueChart');

        if (mctx) {
            membersChartInstance = new Chart(mctx, {
                type: 'line',
                data: {
                    labels: @json($chartLabels ?? []),
                    datasets: [{
                        label: 'New Members',
                        data: @json($membersChartData ?? []),
                        borderColor: '#0d6efd',
                        tension: 0.3
                    }]
                }
            });
        }

        if (rctx) {
            revenueChartInstance = new Chart(rctx, {
                type: 'bar',
                data: {
                    labels: @json($chartLabels ?? []),
                    datasets: [{
                        label: 'Revenue (RWF)',
                        data: @json($revenueChartData ?? []),
                        backgroundColor: '#dc3545'
                    }]
                }
            });
        }

        // Refresh Dashboard
        const refreshBtn = document.getElementById('refreshDashboard');

        if (refreshBtn) {
            refreshBtn.addEventListener('click', async function () {
                const btn = this;
                btn.disabled = true;

                try {
                    const res = await fetch('{{ route('dashboard.refresh') }}', {
                        headers: { 'Accept': 'application/json' }
                    });

                    const data = await res.json();

                    // Update stats
                    document.getElementById('memberCount').innerText = data.stats.memberCount ?? 0;
                    document.getElementById('todayCheckIns').innerText = data.stats.todayCheckIns ?? 0;
                    document.getElementById('todayRevenue').innerText = 'RWF ' + (data.stats.todayRevenue ?? 0);
                    document.getElementById('totalRevenue').innerText = 'RWF ' + (data.stats.totalRevenue ?? 0);

                    // Update charts
                    if (membersChartInstance) {
                        membersChartInstance.data.labels = data.chart.labels ?? [];
                        membersChartInstance.data.datasets[0].data = data.chart.members ?? [];
                        membersChartInstance.update();
                    }

                    if (revenueChartInstance) {
                        revenueChartInstance.data.labels = data.chart.labels ?? [];
                        revenueChartInstance.data.datasets[0].data = data.chart.revenue ?? [];
                        revenueChartInstance.update();
                    }

                } catch (e) {
                    window.location.reload();
                } finally {
                    btn.disabled = false;
                }
            });
        }
    </script>

</x-app-layout>