<x-app-layout>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Gyms</h3>
            <a href="{{ route('gyms.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> New Gym</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-autohide">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
            <x-sophisticated-table :headers="['Logo', 'Gym Information', 'Contact', 'Members', 'Actions']">
                @forelse($gyms as $gym)
                    <tr>
                        <td>
                            @if($gym->logo)
                                <img src="{{ asset('storage/' . $gym->logo) }}" alt="{{ $gym->name }}" class="rounded shadow-sm" style="width: 60px; height: 60px; object-fit: cover; border: 2px solid #f8f9fa;">
                            @else
                                <div class="bg-gradient rounded d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 60px; height: 60px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                    <i class="bi bi-building text-white fs-4"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div>
                                <div class="fw-bold text-dark" style="font-size: 1.05rem;">{{ $gym->name }}</div>
                                @if($gym->address)
                                    <small class="text-muted">
                                        <i class="bi bi-geo-alt me-1"></i>{{ $gym->address }}
                                    </small>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div>
                                @if($gym->email)
                                    <div class="mb-1">
                                        <i class="bi bi-envelope text-primary me-1"></i>
                                        <small>{{ $gym->email }}</small>
                                    </div>
                                @endif
                                @if($gym->phone)
                                    <div>
                                        <i class="bi bi-telephone text-success me-1"></i>
                                        <small>{{ $gym->phone }}</small>
                                    </div>
                                @endif
                                @if(!$gym->email && !$gym->phone)
                                    <span class="text-muted">—</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-primary bg-gradient" style="font-size: 0.9rem; padding: 0.5rem 0.75rem;">
                                <i class="bi bi-people me-1"></i>{{ $gym->members()->count() }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('gyms.show', $gym) }}" class="btn btn-outline-info" title="View Profile">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('gyms.switch', $gym) }}" class="btn btn-outline-primary" title="Switch to this gym">
                                    <i class="bi bi-arrow-repeat"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-building" style="font-size: 4rem; opacity: 0.3;"></i>
                                <p class="mt-3 mb-2 fs-5">No gyms found</p>
                                <p class="text-muted small mb-3">Start by creating your first gym</p>
                                <a href="{{ route('gyms.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i> Create First Gym
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </x-sophisticated-table>
            <div class="card-footer bg-white border-0 px-3">
                {{ $gyms->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
