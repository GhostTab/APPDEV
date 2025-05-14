@extends('layouts.app')

@section('content')
<div class="min-vh-100 bg-light">
    <div class="bg-primary">
        @include('components.navbar')
    </div>
    
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0 animate-fade-in">Manage Your Events</h2>
            <a href="{{ route('events.create') }}" class="btn btn-primary animate-fade-in">
                <i class="fas fa-plus me-2"></i>Create New Event
            </a>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show animate-fade-in" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Filter Buttons -->
        <div class="filter-buttons mb-4">
            <div class="btn-group" role="group">
                <a href="{{ route('events.manage') }}" 
                   class="btn {{ !request('filter') ? 'btn-primary' : 'btn-outline-primary' }}">
                    <i class="fas fa-list me-2"></i>All Events
                </a>
                <a href="{{ route('events.manage', ['filter' => 'current']) }}" 
                   class="btn {{ request('filter') === 'current' ? 'btn-primary' : 'btn-outline-primary' }}">
                    <i class="fas fa-calendar-check me-2"></i>Current Events
                </a>
                <a href="{{ route('events.manage', ['filter' => 'done']) }}" 
                   class="btn {{ request('filter') === 'done' ? 'btn-primary' : 'btn-outline-primary' }}">
                    <i class="fas fa-check-circle me-2"></i>Done Events
                </a>
                <a href="{{ route('events.manage', ['filter' => 'removed']) }}" 
                   class="btn {{ request('filter') === 'removed' ? 'btn-primary' : 'btn-outline-primary' }}">
                    <i class="fas fa-ban me-2"></i>Removed Events
                </a>
            </div>
        </div>

        <!-- Event Count Badge -->
        <div class="event-count-badge mb-4">
            <span class="badge bg-primary">
                {{ $events->total() }} {{ Str::plural('event', $events->total()) }} found
            </span>
        </div>

        <div class="row g-4">
            @forelse($events as $event)
            <div class="col-md-6 col-lg-4 animate-fade-up" style="animation-delay: {{ $loop->index * 0.1 }}s">
                <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden event-card">
                    <div class="position-relative">
                        @if($event->image)
                            <div class="card-img-container {{ $event->status === 'removed' ? 'grayscale' : '' }}">
                                <img src="{{ asset($event->image) }}" class="card-img-top" alt="{{ $event->title }}">
                            </div>
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center placeholder-img {{ $event->status === 'removed' ? 'grayscale' : '' }}">
                                <i class="fas fa-calendar-alt fa-3x text-muted"></i>
                            </div>
                        @endif
                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge bg-primary rounded-pill px-3 py-2 category-badge">
                                {{ ucfirst($event->category) }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title fw-bold mb-0">{{ $event->title }}</h5>
                            @php
                                $statusClass = [
                                    'pending' => 'bg-warning bg-opacity-10 text-warning',
                                    'approved' => 'bg-success bg-opacity-10 text-success',
                                    'removed' => 'bg-danger bg-opacity-10 text-danger'
                                ][$event->status] ?? 'bg-secondary';
                            @endphp
                            <span class="badge {{ $statusClass }}">
                                {{ ucfirst($event->status) }}
                            </span>
                        </div>
                        <div class="mb-3 text-muted small">
                            <p class="mb-2">
                                <i class="fas fa-calendar me-2"></i>
                                {{ \Carbon\Carbon::parse($event->date)->format('F j, Y') }}
                                <i class="fas fa-clock ms-3 me-2"></i>
                                {{ \Carbon\Carbon::parse($event->time)->format('g:i A') }}
                            </p>
                            <p class="mb-0">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                {{ $event->location }}
                            </p>
                        </div>
                        <p class="card-text">{{ Str::limit($event->description, 100) }}</p>
                    </div>
                    <div class="card-footer bg-white border-0 p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="avatar">
                                    <i class="fas fa-user-circle fa-2x text-muted"></i>
                                </div>
                                <div class="ms-3">
                                    <p class="mb-0 small fw-medium">Posted by</p>
                                    <p class="mb-0 text-muted small">{{ $event->user->name }}</p>
                                </div>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('events.edit', $event->id) }}">
                                            <i class="fas fa-edit me-2"></i>Edit
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('events.destroy', $event->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this event?')">
                                                <i class="fas fa-trash me-2"></i>Delete
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 animate-fade-in">
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <h5>No events found</h5>
                    <p class="text-muted">
                        @if(request('filter') === 'current')
                            You don't have any current events.
                        @elseif(request('filter') === 'done')
                            You don't have any completed events.
                        @elseif(request('filter') === 'removed')
                            You don't have any removed events.
                        @else
                            Create your first event to get started!
                        @endif
                    </p>
                    <a href="{{ route('events.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Create New Event
                    </a>
                </div>
            </div>
            @endforelse
        </div>

        @if($events->hasPages())
        <div class="d-flex justify-content-center mt-5">
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    @if($events->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link">Previous</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $events->previousPageUrl() }}" rel="prev">Previous</a>
                        </li>
                    @endif

                    @foreach($events->getUrlRange(1, $events->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $events->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    @if($events->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $events->nextPageUrl() }}" rel="next">Next</a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link">Next</span>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
        @endif
    </div>
</div>

<style>
/* Animation Classes */
.animate-fade-in {
    animation: fadeIn 0.5s ease-out;
}

.animate-fade-up {
    animation: fadeInUp 0.5s ease-out;
    opacity: 0;
    animation-fill-mode: forwards;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Card Styles */
.event-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    background: #fff;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.event-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.card-img-container {
    height: 200px;
    overflow: hidden;
}

.card-img-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.event-card:hover .card-img-container img {
    transform: scale(1.05);
}

.placeholder-img {
    height: 200px;
    transition: all 0.3s ease;
}

.event-card:hover .placeholder-img {
    background-color: #f8f9fa;
}

.category-badge {
    transition: all 0.3s ease;
    background: linear-gradient(45deg, #3b82f6, #2563eb);
}

.event-card:hover .category-badge {
    transform: scale(1.05);
}

.avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    transition: all 0.3s ease;
}

.event-card:hover .avatar {
    background: #e9ecef;
}

/* Button Styles */
.btn-primary {
    background: linear-gradient(45deg, #3b82f6, #2563eb);
    border: none;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
}

/* Dropdown Styles */
.dropdown-menu {
    border: none;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    padding: 0.5rem;
}

.dropdown-item {
    padding: 0.5rem 1rem;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
}

.dropdown-item i {
    width: 20px;
    text-align: center;
}

/* Alert Animation */
.alert {
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        transform: translateY(-20px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

/* Add these new styles to your existing styles */
.filter-buttons {
    background: white;
    padding: 1rem;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.filter-buttons .btn-group {
    width: 100%;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.filter-buttons .btn {
    flex: 1;
    min-width: 150px;
    white-space: nowrap;
    border-radius: 8px !important;
    transition: all 0.3s ease;
}

.filter-buttons .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
}

.event-count-badge {
    font-size: 0.9rem;
}

.event-count-badge .badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 500;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .filter-buttons .btn-group {
        flex-direction: column;
    }
    
    .filter-buttons .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }
}

/* Add this to your existing styles */
.grayscale {
    filter: grayscale(100%);
    opacity: 0.7;
}

.grayscale img {
    filter: grayscale(100%);
}

/* Update the hover effect to remove grayscale on hover */
.event-card:hover .grayscale {
    filter: grayscale(0%);
    opacity: 1;
}

.event-card:hover .grayscale img {
    filter: grayscale(0%);
}
</style>
@endsection