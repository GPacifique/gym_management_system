@extends('layouts.guest')

@section('content')
<style>
    .gym-registration-wrapper {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 3rem 0;
    }
    
    .registration-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        overflow: hidden;
        animation: slideUp 0.6s ease-out;
    }
    
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .registration-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2.5rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .registration-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: pulse 15s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }
    
    .registration-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 1;
    }
    
    .registration-header p {
        font-size: 1.1rem;
        opacity: 0.95;
        position: relative;
        z-index: 1;
    }
    
    .section-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        border-radius: 10px;
        margin-bottom: 1.5rem;
    }
    
    .section-header i {
        font-size: 1.5rem;
        color: #667eea;
    }
    
    .section-header h4 {
        margin: 0;
        font-weight: 600;
        color: #2d3748;
    }
    
    .form-label {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }
    
    .form-control, .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
        font-size: 0.95rem;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        transform: translateY(-2px);
    }
    
    .form-control.is-invalid {
        border-color: #e53e3e;
    }
    
    .text-danger {
        color: #e53e3e !important;
    }
    
    .invalid-feedback {
        color: #e53e3e;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 10px;
        padding: 0.875rem 2.5rem;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }
    
    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
    }
    
    .btn-outline-secondary {
        border: 2px solid #cbd5e0;
        color: #4a5568;
        border-radius: 10px;
        padding: 0.875rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-outline-secondary:hover {
        background: #f7fafc;
        border-color: #a0aec0;
        transform: translateY(-2px);
    }
    
    .alert-info {
        background: linear-gradient(135deg, #ebf8ff 0%, #bee3f8 100%);
        border: none;
        border-left: 4px solid #3182ce;
        border-radius: 10px;
        padding: 1rem 1.5rem;
    }
    
    .alert-danger {
        background: linear-gradient(135deg, #fff5f5 0%, #fed7d7 100%);
        border: none;
        border-left: 4px solid #e53e3e;
        border-radius: 10px;
        padding: 1rem 1.5rem;
    }
    
    .form-check-input:checked {
        background-color: #667eea;
        border-color: #667eea;
    }
    
    .trial-badge {
        display: inline-block;
        background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
        color: white;
        padding: 0.5rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        margin-bottom: 1rem;
        animation: bounce 2s infinite;
    }
    
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    
    .feature-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 2rem;
    }
    
    .feature-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: white;
    }
    
    .feature-item i {
        color: #48bb78;
        font-size: 1.2rem;
    }
    
    /* Smooth scroll behavior */
    html {
        scroll-behavior: smooth;
    }
    
    /* Logo preview */
    #logoPreview {
        display: none;
        margin-top: 1rem;
        max-width: 200px;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    /* Loading spinner for submit */
    .btn-primary:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }
    
    .spinner-border-sm {
        width: 1rem;
        height: 1rem;
        border-width: 0.15rem;
    }
</style>

<div class="gym-registration-wrapper">
<div class="container-fluid px-4 py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9">
            <!-- Registration Form Card -->
            <div class="registration-card">
                <!-- Header Section -->
                <div class="registration-header">
                    <div class="trial-badge">
                        <i class="bi bi-star-fill me-2"></i>30-Day Free Trial
                    </div>
                    <h1>
                        <i class="bi bi-building"></i>
                        Register Your Gym
                    </h1>
                    <p class="mb-0">
                        Start managing your fitness center with our all-in-one platform
                    </p>
                    <div class="feature-list mt-3">
                        <div class="feature-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Member Management</span>
                        </div>
                        <div class="feature-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Class Scheduling</span>
                        </div>
                        <div class="feature-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Payment Tracking</span>
                        </div>
                        <div class="feature-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Analytics & Reports</span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4 p-md-5">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <strong>Please correct the following errors:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('gym.register.store') }}" enctype="multipart/form-data" id="registrationForm">
                        @csrf

                        <!-- Gym Information Section -->
                        <div class="mb-4">
                            <div class="section-header">
                                <i class="bi bi-building"></i>
                                <h4>Gym Information</h4>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label for="gym_name" class="form-label">
                                        Gym Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('gym_name') is-invalid @enderror" 
                                           id="gym_name" 
                                           name="gym_name" 
                                           value="{{ old('gym_name') }}" 
                                           required 
                                           placeholder="e.g., FitPro Gym & Fitness">
                                    @error('gym_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="gym_timezone" class="form-label">
                                        Timezone <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('gym_timezone') is-invalid @enderror" 
                                            id="gym_timezone" 
                                            name="gym_timezone" 
                                            required>
                                        <option value="">Select Timezone</option>
                                        <option value="UTC" {{ old('gym_timezone') == 'UTC' ? 'selected' : '' }}>UTC</option>
                                        <option value="America/New_York" {{ old('gym_timezone') == 'America/New_York' ? 'selected' : '' }}>Eastern Time (US & Canada)</option>
                                        <option value="America/Chicago" {{ old('gym_timezone') == 'America/Chicago' ? 'selected' : '' }}>Central Time (US & Canada)</option>
                                        <option value="America/Denver" {{ old('gym_timezone') == 'America/Denver' ? 'selected' : '' }}>Mountain Time (US & Canada)</option>
                                        <option value="America/Los_Angeles" {{ old('gym_timezone') == 'America/Los_Angeles' ? 'selected' : '' }}>Pacific Time (US & Canada)</option>
                                        <option value="Europe/London" {{ old('gym_timezone') == 'Europe/London' ? 'selected' : '' }}>London</option>
                                        <option value="Europe/Paris" {{ old('gym_timezone') == 'Europe/Paris' ? 'selected' : '' }}>Paris</option>
                                        <option value="Asia/Dubai" {{ old('gym_timezone') == 'Asia/Dubai' ? 'selected' : '' }}>Dubai</option>
                                        <option value="Asia/Kolkata" {{ old('gym_timezone') == 'Asia/Kolkata' ? 'selected' : '' }}>India</option>
                                        <option value="Asia/Singapore" {{ old('gym_timezone') == 'Asia/Singapore' ? 'selected' : '' }}>Singapore</option>
                                        <option value="Australia/Sydney" {{ old('gym_timezone') == 'Australia/Sydney' ? 'selected' : '' }}>Sydney</option>
                                    </select>
                                    @error('gym_timezone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12">
                                    <label for="gym_address" class="form-label">
                                        Address <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control @error('gym_address') is-invalid @enderror" 
                                              id="gym_address" 
                                              name="gym_address" 
                                              rows="2" 
                                              required 
                                              placeholder="123 Main Street, City, State, ZIP">{{ old('gym_address') }}</textarea>
                                    @error('gym_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="gym_phone" class="form-label">
                                        Phone Number <span class="text-danger">*</span>
                                    </label>
                                    <input type="tel" 
                                           class="form-control @error('gym_phone') is-invalid @enderror" 
                                           id="gym_phone" 
                                           name="gym_phone" 
                                           value="{{ old('gym_phone') }}" 
                                           required 
                                           placeholder="+1 (555) 123-4567">
                                    @error('gym_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-8">
                                    <label for="gym_email" class="form-label">
                                        Gym Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" 
                                           class="form-control @error('gym_email') is-invalid @enderror" 
                                           id="gym_email" 
                                           name="gym_email" 
                                           value="{{ old('gym_email') }}" 
                                           required 
                                           placeholder="info@yourgym.com">
                                    @error('gym_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-8">
                                    <label for="gym_description" class="form-label">
                                        Description (Optional)
                                    </label>
                                    <textarea class="form-control @error('gym_description') is-invalid @enderror" 
                                              id="gym_description" 
                                              name="gym_description" 
                                              rows="3" 
                                              placeholder="Brief description of your gym, facilities, and specialties...">{{ old('gym_description') }}</textarea>
                                    @error('gym_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">You can add more details later from your gym profile.</small>
                                </div>

                                <div class="col-md-4">
                                    <label for="gym_logo" class="form-label">
                                        Gym Logo (Optional)
                                    </label>
                                    <input type="file" 
                                           class="form-control @error('gym_logo') is-invalid @enderror" 
                                           id="gym_logo" 
                                           name="gym_logo" 
                                           accept="image/*">
                                    <img id="logoPreview" src="" alt="Logo preview">
                                    @error('gym_logo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted d-block mt-1">Max 2MB</small>
                                </div>
                            </div>
                        </div>

                        <!-- Owner Information Section -->
                        <div class="mb-4">
                            <div class="section-header">
                                <i class="bi bi-person-circle"></i>
                                <h4>Owner Account Information</h4>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-7">
                                    <label for="owner_name" class="form-label">
                                        Your Full Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('owner_name') is-invalid @enderror" 
                                           id="owner_name" 
                                           name="owner_name" 
                                           value="{{ old('owner_name') }}" 
                                           required 
                                           placeholder="John Doe">
                                    @error('owner_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-5">
                                    <label for="owner_email" class="form-label">
                                        Your Email Address <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" 
                                           class="form-control @error('owner_email') is-invalid @enderror" 
                                           id="owner_email" 
                                           name="owner_email" 
                                           value="{{ old('owner_email') }}" 
                                           required 
                                           placeholder="john@example.com">
                                    @error('owner_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">You'll use this email to log in.</small>
                                </div>

                                <div class="col-md-6">
                                    <label for="owner_password" class="form-label">
                                        Password <span class="text-danger">*</span>
                                    </label>
                                    <input type="password" 
                                           class="form-control @error('owner_password') is-invalid @enderror" 
                                           id="owner_password" 
                                           name="owner_password" 
                                           required 
                                           placeholder="••••••••">
                                    @error('owner_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Minimum 8 characters</small>
                                </div>

                                <div class="col-md-6">
                                    <label for="owner_password_confirmation" class="form-label">
                                        Confirm Password <span class="text-danger">*</span>
                                    </label>
                                    <input type="password" 
                                           class="form-control" 
                                           id="owner_password_confirmation" 
                                           name="owner_password_confirmation" 
                                           required 
                                           placeholder="••••••••">
                                </div>
                            </div>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input @error('terms') is-invalid @enderror" 
                                       type="checkbox" 
                                       id="terms" 
                                       name="terms" 
                                       value="1"
                                       {{ old('terms') ? 'checked' : '' }}
                                       required>
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="#" target="_blank">Terms of Service</a> and 
                                    <a href="#" target="_blank">Privacy Policy</a> <span class="text-danger">*</span>
                                </label>
                                @error('terms')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Trial Information -->
                        <div class="alert alert-info mb-4">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>30-Day Free Trial:</strong> No credit card required. Full access to all features. 
                            Cancel anytime during the trial period.
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-between align-items-center">
                            <a href="{{ route('welcome') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Back to Home
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg px-5" id="submitBtn">
                                <i class="bi bi-check-circle me-2"></i>Register Gym
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="text-center mt-4">
                <p class="text-white">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="text-white text-decoration-none fw-semibold" style="text-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                        <u>Sign in here</u>
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Logo preview
    const logoInput = document.getElementById('gym_logo');
    const logoPreview = document.getElementById('logoPreview');
    
    if (logoInput) {
        logoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    logoPreview.src = e.target.result;
                    logoPreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                logoPreview.style.display = 'none';
            }
        });
    }
    
    // Form validation feedback
    const form = document.getElementById('registrationForm');
    const submitBtn = document.getElementById('submitBtn');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            // Add loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
        });
        
        // Real-time validation for password match
        const password = document.getElementById('owner_password');
        const confirmPassword = document.getElementById('owner_password_confirmation');
        
        if (password && confirmPassword) {
            confirmPassword.addEventListener('input', function() {
                if (this.value && password.value !== this.value) {
                    this.setCustomValidity('Passwords do not match');
                    this.classList.add('is-invalid');
                } else {
                    this.setCustomValidity('');
                    this.classList.remove('is-invalid');
                }
            });
        }
    }
    
    // Smooth scroll to errors
    const firstError = document.querySelector('.is-invalid');
    if (firstError) {
        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        firstError.focus();
    }
    
    // Add floating label effect
    const formControls = document.querySelectorAll('.form-control, .form-select');
    formControls.forEach(control => {
        control.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        control.addEventListener('blur', function() {
            if (!this.value) {
                this.parentElement.classList.remove('focused');
            }
        });
    });
});
</script>
@endsection
