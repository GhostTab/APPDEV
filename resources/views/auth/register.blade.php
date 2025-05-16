@extends('layouts.app')

@section('styles')
<style>
.card {
    transition: all 0.3s ease;
    opacity: 0;
    transform: translateY(20px);
    animation: slideIn 0.5s ease forwards;
}

@keyframes slideIn {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Modal styles */
.modal {
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-dialog {
    max-width: 500px;
    margin: 1.75rem auto;
}

.modal-content {
    border-radius: 0.5rem;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    z-index: -10;
    width: 100vw;
    height: 100vh;
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-open {
    overflow: hidden;
    padding-right: 0 !important;
}

/* Update modal styles */
.modal {
    background: none !important;
}

.modal-backdrop {
    display: none !important;
}

.modal-dialog {
    max-width: 500px;
    margin: 1.75rem auto;
    z-index: 1056;
}

.modal-content {
    border-radius: 0.5rem;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

/* Loading overlay styles */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(5px);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.loading-content {
    text-align: center;
}

.spinner {
    width: 50px;
    height: 50px;
    border: 4px solid rgba(255, 255, 255, 0.1);
    border-left-color: #fff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.loading-content p {
    font-size: 1.1rem;
    margin-top: 1rem;
}

.btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none !important;
    box-shadow: none !important;
}
</style>
@endsection

@section('content')
<div class="min-vh-100 position-relative">
    <!-- Add loading overlay -->
    <div id="loadingOverlay" class="loading-overlay d-none">
        <div class="loading-content">
            <div class="spinner"></div>
            <p class="mt-3 text-white">Creating your account...</p>
        </div>
    </div>

    <!-- Background Overlay -->
    <!-- Update the background overlay z-index -->
    <div class="position-absolute w-100 h-100" style="
        background: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)),
        url('{{ asset('images/San.png') }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        z-index: -1;  <!-- Changed from 0 to -1 -->
    "></div>

    @include('components.navbar')
    
    <div class="container py-3 h-90 position-relative" style="font-family: 'Poppins', sans-serif; z-index: 1;">
        <div class="row justify-content-center align-items-center h-90">
            <div class="col-md-6">
                <div class="card rounded-4 shadow">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px;">
                                <i class="bi bi-person-plus text-white fs-3"></i>
                            </div>
                            <h2 class="fw-bold mb-2" style="font-family: 'Poppins', sans-serif;">Create an account</h2>
                            <p class="text-muted" style="font-family: 'Poppins', sans-serif;">Join Tacloban Events Hub community</p>
                        </div>

                        <form method="POST" action="{{ route('register.post') }}">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <input type="text" class="form-control form-control-lg" name="first_name" placeholder="First Name" pattern="[A-Za-z\s]+" title="Only letters are allowed" style="font-family: 'Poppins', sans-serif;" required>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control form-control-lg" name="middle_name" placeholder="Middle Name" pattern="[A-Za-z\s]*" title="Only letters are allowed" style="font-family: 'Poppins', sans-serif;">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control form-control-lg" name="last_name" placeholder="Last Name" pattern="[A-Za-z\s]+" title="Only letters are allowed" style="font-family: 'Poppins', sans-serif;" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <input type="email" class="form-control form-control-lg" name="email" placeholder="Email" style="font-family: 'Poppins', sans-serif;" required>
                            </div>
                            <div class="mb-3 position-relative">
                                <input type="password" class="form-control form-control-lg" name="password" placeholder="Password" style="font-family: 'Poppins', sans-serif;" id="password" required>
                                <i class="bi bi-eye-slash position-absolute top-50 end-0 translate-middle-y me-3 text-muted" style="cursor: pointer;" onclick="togglePassword('password', this)"></i>
                            </div>
                            <div class="mb-4 position-relative">
                                <input type="password" class="form-control form-control-lg" name="password_confirmation" placeholder="Confirm Password" style="font-family: 'Poppins', sans-serif;" id="password_confirmation" required>
                                <i class="bi bi-eye-slash position-absolute top-50 end-0 translate-middle-y me-3 text-muted" style="cursor: pointer;" onclick="togglePassword('password_confirmation', this)"></i>
                            </div>
                            <!-- Add error messages display -->
                            @if ($errors->any())
                                <div class="alert alert-danger mb-3">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a>
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2 mb-3" id="registerButton">Create Account</button>
                        </form>
                        <!-- Terms and Conditions Modal -->
                        <!-- Update the modal -->
                        <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true" data-bs-backdrop="false">
                            <div class="modal-dialog modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <h6>1. Acceptance of Terms</h6>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                        
                                        <h6>2. User Registration</h6>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                        
                                        <h6>3. Privacy Policy</h6>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                        
                                        <h6>4. User Conduct</h6>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                        
                                        <h6>5. Content Guidelines</h6>
                                        <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Add this before closing body tag -->
                        @section('scripts')
                        <script>
                            function togglePassword(inputId, icon) {
                                const input = document.getElementById(inputId);
                                if (input.type === 'password') {
                                    input.type = 'text';
                                    icon.classList.replace('bi-eye-slash', 'bi-eye');
                                } else {
                                    input.type = 'password';
                                    icon.classList.replace('bi-eye', 'bi-eye-slash');
                                }
                            }

                            document.addEventListener('DOMContentLoaded', function() {
                                const modal = document.getElementById('termsModal');
                                modal.addEventListener('hidden.bs.modal', function () {
                                    document.body.style.overflow = 'auto';
                                    document.body.style.paddingRight = '0';
                                });

                                // Add form submission handler
                                const form = document.querySelector('form');
                                const registerButton = document.getElementById('registerButton');
                                const loadingOverlay = document.getElementById('loadingOverlay');
                                
                                form.addEventListener('submit', function(e) {
                                    // Prevent the default form submission
                                    e.preventDefault();
                                    
                                    // Disable the button immediately
                                    registerButton.disabled = true;
                                    registerButton.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Creating Account...';
                                    
                                    // Show loading overlay
                                    loadingOverlay.classList.remove('d-none');
                                    
                                    // Submit the form after a brief delay to ensure UI updates
                                    setTimeout(() => {
                                        this.submit();
                                    }, 10);
                                });
                            });
                        </script>
                        @endsection
                        <div class="text-center" style="font-family: 'Poppins', sans-serif;">
                            <span class="text-muted">Already have an account? </span>
                            <a href="{{ route('login') }}" class="text-primary text-decoration-none fw-semibold">Sign in</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection