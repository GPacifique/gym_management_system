<x-app-layout>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Gyms</h3>
            <a href="{{ route('gyms.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> New Gym</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-autohide">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Logo</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Phone</th>
                            <th>Members</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($gyms as $gym)
                            <tr>
                                <td>
                                    @if($gym->logo)
                                        <img src="{{ asset('storage/' . $gym->logo) }}" alt="{{ $gym->name }}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-inline-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                            <i class="bi bi-building text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $gym->name }}</strong>
                                    @if($gym->email)
                                        <br><small class="text-muted">{{ $gym->email }}</small>
                                    @endif
                                </td>
                                <td>{{ $gym->address ?? '-' }}</td>
                                <td>{{ $gym->phone ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ $gym->members()->count() }}</span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('gyms.show', $gym) }}" class="btn btn-sm btn-outline-info" title="View Profile">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('gyms.switch', $gym) }}" class="btn btn-sm btn-outline-secondary" title="Switch to this gym">
                                        <i class="bi bi-arrow-repeat"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted">No gyms yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $gyms->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
