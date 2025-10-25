<x-app-layout>
    <div class="container mt-4">
        <h3 class="mb-3">Create Gym</h3>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('gyms.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Timezone</label>
                            <input type="text" name="timezone" class="form-control" value="{{ config('app.timezone', 'UTC') }}">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                    </div>
                    <div class="mt-3 d-flex gap-2">
                        <a href="{{ route('gyms.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
