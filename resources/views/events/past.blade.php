@extends('layouts.app')

@section('content')
<div class="min-vh-100 bg-light">
    <div class="bg-primary">
        @include('components.navbar')
    </div>
    
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0 animate-fade-in">Past Events</h2>
            <div class="d-flex gap-3 align-items-center">
                <form action="{{ route('events.past') }}" method="GET" class="d-flex gap-3">
                    <div class="search-bar">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" 
                                name="search" 
                                class="form-control border-start-0" 
                                placeholder="Search past events..." 
                                value="{{ request('search') }}"
                                style="font-family: 'Poppins', sans-serif;">
                        </div>
                    </div>
                    <div class="category-filter">
                        <select class="form-select" name="category" id="categoryFilter">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                    {{ ucfirst($category) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <div class="row g-4" id="eventsContainer">
            @forelse($events as $event)
            <div class="col-md-6 col-lg-4 animate-fade-up" style="animation-delay: {{ $loop->index * 0.1 }}s">
                <a href="{{ route('events.show', $event) }}" class="text-decoration-none text-dark">
                    <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden event-card">
                        <div class="position-relative">
                            @if($event->image)
                                <div class="card-img-container">
                                    <img src="{{ asset($event->image) }}" class="card-img-top" alt="{{ $event->title }}">
                                </div>
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center placeholder-img">
                                    <i class="fas fa-calendar-alt fa-3x text-muted"></i>
                                </div>
                            @endif
                            <div class="position-absolute top-0 end-0 m-3">
                                <span class="badge bg-secondary rounded-pill px-3 py-2 category-badge">
                                    {{ ucfirst($event->category) }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <h5 class="card-title fw-bold mb-3">{{ $event->title }}</h5>
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
                        <div class="card-footer bg-white border-0 p-4 pt-0">
                            <div class="d-flex align-items-center">
                                <div class="avatar">
                                    @if($event->user->profile_image)
                                        <img src="{{ asset($event->user->profile_image) }}" class="rounded-circle" width="40" height="40" alt="Organizer" style="object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <span class="text-primary fw-bold">{{ strtoupper(substr($event->user->name, 0, 1)) }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="ms-3">
                                    <p class="mb-0 small fw-medium">Posted by</p>
                                    <p class="mb-0 text-muted small">{{ $event->user->name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @empty
            <div class="col-12 animate-fade-in">
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <h5>No past events found</h5>
                    <p class="text-muted">Check back later for more events!</p>
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
    background: linear-gradient(45deg, #6c757d, #495057);
}

.event-card:hover .category-badge {
    transform: scale(1.05);
}

/* Search and Filter Styles */
.search-bar {
    min-width: 250px;
}

.search-bar .input-group {
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.search-bar .input-group:focus-within {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.search-bar .form-control {
    border-left: none;
    padding-left: 0;
    height: 38px;
    font-size: 0.9rem;
}

.search-bar .input-group-text {
    border-right: none;
    background-color: white;
}

.category-filter,
.sort-filter {
    min-width: 200px;
}

.category-filter .form-select,
.sort-filter .form-select {
    background-color: #fff;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 0.5rem 1rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    height: 38px;
    font-size: 0.9rem;
    color: #495057;
    cursor: pointer;
}

.category-filter .form-select:focus,
.sort-filter .form-select:focus {
    border-color: #6c757d;
    box-shadow: 0 0 0 0.25rem rgba(108, 117, 125, 0.25);
    outline: none;
}

@media (max-width: 768px) {
    .search-bar {
        min-width: 200px;
    }
    
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1rem;
    }
    
    .d-flex.gap-3 {
        width: 100%;
        flex-wrap: wrap;
    }
    
    .search-bar,
    .category-filter,
    .sort-filter {
        width: 100%;
    }
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categoryFilter = document.getElementById('categoryFilter');
    const sortFilter = document.getElementById('sortFilter');
    
    if (categoryFilter) {
        categoryFilter.addEventListener('change', function() {
            this.form.submit();
        });
    }
    
    if (sortFilter) {
        sortFilter.addEventListener('change', function() {
            this.form.submit();
        });
    }
});
</script>
@endpush
@endsection 