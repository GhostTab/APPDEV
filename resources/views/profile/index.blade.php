@extends('layouts.app')

@section('content')
<div class="min-vh-100">
    <div class="bg-primary">
        @include('components.navbar')
    </div>
    
    <div class="container py-5" style="font-family: 'Poppins', sans-serif;">
        <div class="d-flex align-items-center gap-3 mb-4">
            <h2 class="fw-bold mb-0">Profile Settings</h2>
            @if(Auth::user()->role_id === 1)
                <span class="badge bg-primary px-3 py-2">Admin</span>
            @elseif(Auth::user()->organizerApplication && Auth::user()->organizerApplication->status === 'pending')
                <span class="badge bg-warning px-3 py-2">Application Pending</span>
            @elseif(Auth::user()->organizerApplication && Auth::user()->organizerApplication->status === 'rejected')
                <span class="badge bg-danger px-3 py-2">Application Rejected</span>
            @elseif(Auth::user()->role_id === 2)
                <span class="badge bg-success px-3 py-2">Event Organizer</span>
            @endif
        </div>

        <div class="row">
            <!-- Profile Picture Section -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title mb-3">Profile Picture</h5>
                        <div class="mb-3">
                            @if(Auth::user()->profile_image)
                                <img src="{{ asset(Auth::user()->profile_image) }}" 
                                     class="rounded-circle mb-3" 
                                     width="150" 
                                     height="150" 
                                     alt="Profile"
                                     onerror="this.onerror=null; this.src='{{ asset('images/default-profile.png') }}';">
                            @else
                                <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center mb-3" style="width: 150px; height: 150px;">
                                    <span class="text-white display-4">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                </div>
                            @endif
                        </div>
                        <form action="{{ route('profile.update-image') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <input type="file" class="form-control" name="profile_image" accept="image/*">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Update Picture</button>
                        </form>
                    </div>
                </div>
            </div>
    
            <!-- Personal Information Section -->
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Personal Information</h5>
                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('PUT')
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">First Name</label>
                                    <input type="text" class="form-control" name="first_name" value="{{ Auth::user()->first_name }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Middle Name</label>
                                    <input type="text" class="form-control" name="middle_name" value="{{ Auth::user()->middle_name }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" class="form-control" name="last_name" value="{{ Auth::user()->last_name }}" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control" name="email" value="{{ Auth::user()->email }}" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
    
                <!-- Change Password Section -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Change Password</h5>
                        <form method="POST" action="{{ route('profile.update-password') }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label">Current Password</label>
                                <input type="password" class="form-control" name="current_password" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">New Password</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" name="password_confirmation" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@endsection

@section('styles')
<style>
.card {
    border-radius: 10px;
    border: none;
}

.form-control {
    border-radius: 8px;
    padding: 10px 15px;
}

.btn {
    border-radius: 8px;
    padding: 8px 20px;
}
</style>
@endsection