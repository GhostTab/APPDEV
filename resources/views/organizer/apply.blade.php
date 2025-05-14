@extends('layouts.app')

@section('content')
@if(!(auth()->user()->role_id === 1) && 
    (!(auth()->user()->role_id === 2) || 
     (auth()->user()->organizerApplication && 
      (auth()->user()->organizerApplication->status === 'pending' || 
       auth()->user()->organizerApplication->status === 'rejected'))))

       <div class="bg-primary" style="z-index: 2;">
            @include('components.navbar')
        </div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if(auth()->user()->organizerApplication)
                @if(auth()->user()->organizerApplication->status === 'pending')
                    <div class="card shadow-sm rounded-4 border-0">
                        <div class="card-body p-5 text-center">
                            <div class="rounded-circle bg-warning bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                                <i class="bi bi-hourglass-split text-warning fs-1"></i>
                            </div>
                            <h2 class="fw-bold mb-3">Application Pending</h2>
                            <p class="text-muted mb-0">Your application is currently under review. We'll notify you once there's an update.</p>
                        </div>
                    </div>
                @elseif(auth()->user()->organizerApplication->status === 'rejected')
                    <div class="card shadow-sm rounded-4 border-0">
                        <div class="card-body p-5">
                            <div class="text-center mb-4">
                                <div class="rounded-circle bg-danger bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                                    <i class="bi bi-x-circle text-danger fs-1"></i>
                                </div>
                                <h2 class="fw-bold mb-3">Application Rejected</h2>
                                <p class="text-muted mb-4">Unfortunately, your application was not approved. You may submit a new application if you wish.</p>
                            </div>
                            
                            <form action="{{ route('organizer.apply.submit') }}" method="POST" class="needs-validation" novalidate>
                                @csrf
                                <div class="mb-4">
                                    <label class="form-label fw-medium">Experience</label>
                                    <textarea class="form-control form-control-lg" name="experience" rows="4" 
                                        placeholder="Brief description of your event planning experience (if any)" 
                                        required>{{ old('experience') }}</textarea>
                                    @error('experience')
                                        <div class="text-danger small mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <button type="submit" class="btn btn-primary btn-lg w-100 py-3 mb-3">
                                    <i class="fas fa-paper-plane me-2"></i>Submit Application
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            @else
                <div class="card shadow-sm rounded-4 border-0">
                    <div class="card-body p-5">
                        <div class="text-center mb-5">
                            <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                                <i class="bi bi-person-plus-fill text-white fs-1"></i>
                            </div>
                            <h2 class="fw-bold mb-3">Become an Event Organizer</h2>
                            <p class="text-muted mb-0">Apply to post your own events on Tacloban Events Hub</p>
                        </div>
                        
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        
                        <form action="{{ route('organizer.apply.submit') }}" method="POST" class="needs-validation" novalidate>
                            @csrf
                            <div class="mb-4">
                                <label class="form-label fw-medium">Experience</label>
                                <textarea class="form-control form-control-lg" name="experience" rows="4" 
                                    placeholder="Brief description of your event planning experience (if any)" 
                                    required>{{ old('experience') }}</textarea>
                                @error('experience')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-lg w-100 py-3 mb-3">
                                <i class="fas fa-paper-plane me-2"></i>Submit Application
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endif

<style>
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}

.form-control {
    border: 1px solid #e0e0e0;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
}

.btn-primary {
    background: linear-gradient(45deg, #0d6efd, #0a58ca);
    border: none;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
}

.alert {
    border: none;
    border-radius: 12px;
}

.alert-success {
    background-color: #d1e7dd;
    color: #0f5132;
}

.alert-danger {
    background-color: #f8d7da;
    color: #842029;
}

.rounded-circle {
    transition: all 0.3s ease;
}

.card:hover .rounded-circle {
    transform: scale(1.1);
}
</style>

<script>
// Form validation
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})()
</script>
@endsection
