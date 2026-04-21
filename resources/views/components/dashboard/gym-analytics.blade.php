<div class="row g-4">

    <!-- TOTAL MEMBERS -->
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <h6>Total Members</h6>
                <h2 class="text-primary">{{ $totalMembers ?? 0 }}</h2>
            </div>
        </div>
    </div>
    <!-- TOTAL MEMBERS -->
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <h6>Total Members</h6>
                <h2 class="text-primary">{{ $totalMembers ?? 0 }}</h2>
            </div>
        </div>
    </div>

    <!-- RETENTION -->
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <h6>Retention Rate</h6>
                <h2 class="text-success">{{ $retentionRate ?? 0 }}%</h2>
            </div>
        </div>
    </div>

    <!-- YEARLY -->
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <h6>New Members This Year</h6>
                <h2 class="text-warning">{{ $yearlyMembers ?? 0 }}</h2>
            </div>
        </div>
    </div>

</div>