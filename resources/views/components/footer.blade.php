<footer class="mt-5 border-top bg-white">
    <div class="container-fluid py-4">

        <div class="row align-items-center">

            <!-- Left -->
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <div class="text-muted small">
                    © {{ date('Y') }} 
                    <span class="fw-semibold text-dark">
                        {{ config('app.name', 'Gym Manager Pro') }}
                    </span>.
                    All rights reserved.
                </div>

                <div class="text-muted small mt-1">
                    Built with ❤️ for modern gym management
                </div>
            </div>

            <!-- Right -->
            <div class="col-md-6 text-center text-md-end">

                <a href="#" class="text-muted text-decoration-none me-3 footer-link">
                    Privacy Policy
                </a>

                <a href="#" class="text-muted text-decoration-none me-3 footer-link">
                    Terms
                </a>

                <a href="#" class="text-muted text-decoration-none footer-link">
                    Support
                </a>

            </div>

        </div>
    </div>
</footer>

<style>
.footer-link {
    transition: all 0.2s ease;
    font-weight: 500;
}

.footer-link:hover {
    color: #0d6efd !important;
}
</style>