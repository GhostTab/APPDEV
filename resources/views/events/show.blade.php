@extends('layouts.app')

@section('content')
<div class="min-vh-100 bg-light">
    <div class="bg-primary">
        @include('components.navbar')
    </div>

    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8">
                <!-- Event Image -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    @if($event->image)
                        <img src="{{ asset($event->image) }}" class="card-img-top" alt="{{ $event->title }}" style="height: 500px; object-fit: cover;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 500px;">
                            <i class="fas fa-calendar-alt fa-5x text-muted"></i>
                        </div>
                    @endif
                </div>

                <!-- Event Details -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div>
                                <span class="badge bg-primary rounded-pill px-3 py-2 mb-3">{{ ucfirst($event->category) }}</span>
                                <h1 class="display-5 fw-bold mb-3">{{ $event->title }}</h1>
                            </div>
                            <div class="text-end">
                                <p class="text-muted mb-1">Posted on</p>
                                <p class="fw-bold mb-0">{{ \Carbon\Carbon::parse($event->created_at)->format('F j, Y') }}</p>
                            </div>
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="icon-circle bg-primary bg-opacity-10 me-3">
                                        <i class="fas fa-calendar text-primary"></i>
                                    </div>
                                    <div>
                                        <p class="text-muted mb-1">Date</p>
                                        <p class="fw-bold mb-0">{{ \Carbon\Carbon::parse($event->date)->format('F j, Y') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="icon-circle bg-primary bg-opacity-10 me-3">
                                        <i class="fas fa-clock text-primary"></i>
                                    </div>
                                    <div>
                                        <p class="text-muted mb-1">Time</p>
                                        <p class="fw-bold mb-0">{{ \Carbon\Carbon::parse($event->time)->format('g:i A') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="icon-circle bg-primary bg-opacity-10 me-3">
                                        <i class="fas fa-map-marker-alt text-primary"></i>
                                    </div>
                                    <div>
                                        <p class="text-muted mb-1">Location</p>
                                        <p class="fw-bold mb-0">{{ $event->location }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="icon-circle bg-primary bg-opacity-10 me-3">
                                        <i class="fas fa-user text-primary"></i>
                                    </div>
                                    <div>
                                        <p class="text-muted mb-1">Organizer</p>
                                        <p class="fw-bold mb-0">{{ $event->user->name }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="event-description">
                            <h4 class="fw-bold mb-3">About This Event</h4>
                            <p class="lead mb-4">{{ $event->description }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Organizer Info -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-4">Organizer Information</h4>
                        <div class="d-flex align-items-center mb-4">
                            <div class="avatar me-3">
                                @if($event->user->profile_image)
                                    <img src="{{ asset($event->user->profile_image) }}" class="rounded-circle" width="60" height="60" alt="Organizer">
                                @else
                                    <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                        <span class="text-primary fw-bold" style="font-size: 1.5rem;">{{ strtoupper(substr($event->user->name, 0, 1)) }}</span>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">{{ $event->user->name }}</h5>
                                <p class="text-muted mb-0">Event Organizer</p>
                            </div>
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-primary">
                                <i class="fas fa-envelope me-2"></i>Contact Organizer
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Similar Events -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-4">Similar Events</h4>
                        @foreach($similarEvents as $similarEvent)
                            <div class="d-flex mb-3">
                                @if($similarEvent->image)
                                    <img src="{{ asset($similarEvent->image) }}" class="rounded-3 me-3" width="80" height="80" alt="{{ $similarEvent->title }}" style="object-fit: cover;">
                                @else
                                    <div class="bg-light rounded-3 me-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                        <i class="fas fa-calendar-alt text-muted"></i>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="fw-bold mb-1">{{ $similarEvent->title }}</h6>
                                    <p class="text-muted small mb-0">{{ \Carbon\Carbon::parse($similarEvent->date)->format('M j, Y') }}</p>
                                    <a href="{{ route('events.show', $similarEvent) }}" class="stretched-link"></a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.icon-circle {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(5px);
    border: 2px solid rgba(255,255,255,0.2);
}

.avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}

.btn-primary {
    background: linear-gradient(45deg, #3b82f6, #2563eb);
    border: none;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
}

@media (max-width: 768px) {
    .display-5 {
        font-size: 2rem;
    }
    
    .icon-circle {
        width: 40px;
        height: 40px;
    }
}
</style>
@endsection 