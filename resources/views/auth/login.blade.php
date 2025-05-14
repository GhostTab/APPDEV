@extends('layouts.app')

@section('content')
<div class="min-vh-100 position-relative">
    <!-- Background Overlay -->
    <div class="position-absolute w-100 h-100" style="
        background: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)),
        url('{{ asset('images/San.png') }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        z-index: 0;
    "></div>

    @include('components.navbar')
    
    <div class="container py-3 h-90 position-relative" style="font-family: 'Poppins', sans-serif; z-index: 1;">
        <div class="row justify-content-center align-items-center h-90">
            <div class="col-md-6">
                <div class="card rounded-4 shadow">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px;">
                                <i class="bi bi-person text-white fs-3"></i>
                            </div>
                            <h2 class="fw-bold mb-2" style="font-family: 'Poppins', sans-serif;">Welcome back!</h2>
                            <p class="text-muted" style="font-family: 'Poppins', sans-serif;">Sign in to your account</p>
                        </div>

                        <form method="POST" action="{{ route('login.post') }}">
                            @csrf
                            <div class="mb-3">
                                <input type="email" class="form-control form-control-lg" name="email" placeholder="Email" style="font-family: 'Poppins', sans-serif;" required>
                            </div>
                            <div class="mb-4 position-relative">
                                <input type="password" class="form-control form-control-lg" name="password" placeholder="Password" style="font-family: 'Poppins', sans-serif;" id="password" required>
                                <i class="bi bi-eye-slash position-absolute top-50 end-0 translate-middle-y me-3 text-muted" style="cursor: pointer;" onclick="togglePassword('password', this)"></i>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-2 mb-3">Sign In</button>
                        </form>
                        
                        <div class="text-center" >
                            <p class="mb-0" style="font-family: 'Poppins', sans-serif;">
                                Don't have an account? 
                                <a href="{{ route('register') }}" class="text-primary text-decoration-none fw-semibold">Sign up</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

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
</style>
@endsection

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
</script>
@endsection