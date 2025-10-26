@props(['lastUpdate' => null, 'isStale' => false, 'title' => null])

<div class="dashboard-header mb-4">
    <div class="d-flex justify-content-between align-items-center">
        @if($title)
            <h1 class="h3 mb-0">{{ $title }}</h1>
        @else
            <div></div>
        @endif
        <button id="refreshDashboard" class="btn btn-primary">
            <i class="bi bi-arrow-clockwise"></i> Refresh Data
        </button>
    </div>

    @if($lastUpdate)
        <div class="text-muted small mt-2">
            Last updated: {{ $lastUpdate }}
            @if($isStale)
                <span class="text-warning ms-2">
                    <i class="bi bi-exclamation-triangle"></i> Data may be outdated
                </span>
            @endif
        </div>
    @endif
</div>

@if(isset($errors) && !empty($errors))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach($errors as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($isStale)
    <div class="alert alert-info" role="alert">
        <i class="bi bi-info-circle-fill"></i> 
        Showing cached data from {{ $lastUpdate }}
    </div>
@endif