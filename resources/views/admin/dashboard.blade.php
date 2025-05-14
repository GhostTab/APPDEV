@extends('layouts.admin')

@section('content')
<div class="admin-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold mb-1">Welcome Back, Admin!</h2>
            <p class="text-muted mb-0">Here's what's happening with your events hub.</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Pending Applications</p>
                        <h2 class="display-4 fw-bold mb-0">{{ $pendingApplications ?? 0 }}</h2>
                        <small class="text-muted">New organizer requests</small>
                    </div>
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                        <i class="bi bi-person-plus text-primary fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Organizers</p>
                        <h2 class="display-4 fw-bold mb-0">{{ $approvedApplications ?? 0 }}</h2>
                        <small class="text-muted">Active organizers</small>
                    </div>
                    <div class="rounded-circle bg-success bg-opacity-10 p-3">
                        <i class="bi bi-check-circle text-success fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Active Events</p>
                        <h2 class="display-4 fw-bold mb-0">{{ $activeEvents ?? 0 }}</h2>
                        <small class="text-muted">Upcoming events</small>
                    </div>
                    <div class="rounded-circle bg-info bg-opacity-10 p-3">
                        <i class="bi bi-calendar-check text-info fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Past Events</p>
                        <h2 class="display-4 fw-bold mb-0">{{ $inactiveEvents ?? 0 }}</h2>
                        <small class="text-muted">Completed events</small>
                    </div>
                    <div class="rounded-circle bg-secondary bg-opacity-10 p-3">
                        <i class="bi bi-calendar-x text-secondary fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection