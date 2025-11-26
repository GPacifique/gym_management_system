@props(['lastUpdate' => null, 'isStale' => false, 'title' => null])

<div class="mb-6">
    <div class="flex items-center justify-between">
        @if($title)
            <h1 class="text-2xl font-semibold text-gray-800">{{ $title }}</h1>
        @else
            <div></div>
        @endif

        <button id="refreshDashboard" class="inline-flex items-center gap-2 px-3 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M4 4a8 8 0 111.757 15.708l-.553-1.657A6 6 0 1014 6h-1l2 2 2-4-4 2v-1a8 8 0 01-9-0z" clip-rule="evenodd" />
            </svg>
            <span>Refresh Data</span>
        </button>
    </div>

    @if($lastUpdate)
        <p class="text-sm text-gray-500 mt-2">
            Last updated: <span class="font-medium text-gray-700">{{ $lastUpdate }}</span>
            @if($isStale)
                <span class="inline-flex items-center text-yellow-600 ml-2">⚠️ Data may be outdated</span>
            @endif
        </p>
    @endif

    @if(isset($errors) && !empty($errors))
        <div class="mt-4 rounded-md bg-yellow-50 border border-yellow-200 p-3 text-yellow-800">
            <ul class="list-disc pl-5">
                @foreach($errors as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($isStale)
        <div class="mt-3 rounded-md bg-blue-50 border border-blue-200 p-3 text-blue-800">
            Showing cached data from <strong>{{ $lastUpdate }}</strong>
        </div>
    @endif
</div>
