<x-app-layout>
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

    <!-- Optional: Charts / Tables -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header">
                    Membership Growth
                </div>
                <div class="card-body">
                    <canvas id="membersChart"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header">
                    Monthly Revenue
                </div>
                <div class="card-body">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx1 = document.getElementById('membersChart');
    const ctx2 = document.getElementById('revenueChart');

    const membersChart = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: @json($chartLabels ?? []),
            datasets: [{
                label: 'New Members',
                data: @json($membersChartData ?? []),
                borderColor: '#007bff',
                tension: 0.3,
                fill: false
            }]
        }
    });

    const revenueChart = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: @json($chartLabels ?? []),
            datasets: [{
                label: 'Revenue ($)',
                data: @json($revenueChartData ?? []),
                backgroundColor: '#dc3545'
            }]
        }
    });

    // Dashboard refresh functionality
    const refreshBtn = document.getElementById('refreshDashboard');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', async function () {
            const button = this;
            const icon = button.querySelector('i');
            button.disabled = true;
            if (icon) icon.classList.add('spin');

            try {
                const res = await fetch('{{ route('dashboard.refresh') }}', { headers: { 'Accept': 'application/json' } });
                if (!res.ok) throw new Error('Failed to refresh');
                const data = await res.json();

                // Update stats in DOM if your header/stats components expose IDs (optional)
                // Update charts
                membersChart.data.labels = data.chart.labels || [];
                membersChart.data.datasets[0].data = data.chart.members || [];
                membersChart.update();

                revenueChart.data.labels = data.chart.labels || [];
                revenueChart.data.datasets[0].data = data.chart.revenue || [];
                revenueChart.update();
            } catch (e) {
                console.error(e);
                // Fallback to full reload
                window.location.reload();
            } finally {
                button.disabled = false;
                if (icon) icon.classList.remove('spin');
            }
        });
    }

    // Initialize all tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>

<style>
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .spin {
        animation: spin 1s linear infinite;
    }

    .alert ul {
        list-style-type: disc;
        padding-left: 20px;
    }

    .stat-card {
        transition: transform 0.2s ease-in-out;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .icon-box {
        transition: transform 0.3s ease;
    }

    .stat-card:hover .icon-box {
        transform: scale(1.1);
    }

    .view-details {
        opacity: 0.8;
        transition: opacity 0.2s ease;
    }

    .stat-card:hover .view-details {
        opacity: 1;
    }
</style>

{{-- Using the x-app-layout component above — sections removed to avoid mixing component slots and section/yield layout styles. --}}

</x-app-layout>
