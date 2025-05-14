<section class="featured-events py-5 bg-light">
    <div class="container" style="max-width: 1200px;">
        <h2 class="text-center fw-bold mb-5 position-relative" style="font-family: 'Poppins', sans-serif;">
            Featured Events
            <div class="title-underline"></div>
        </h2>
        
        <div id="featuredEventsCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
            <div class="carousel-inner rounded-4 shadow-lg">
                @forelse($events->take(5) as $key => $event)
                <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                    <a href="{{ route('events.show', $event) }}" class="text-decoration-none text-white">
                        <div class="position-relative overflow-hidden">
                            @if($event->image)
                                <img src="{{ asset($event->image) }}" class="d-block w-100" alt="{{ $event->title }}" style="height: 500px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 500px;">
                                    <i class="fas fa-calendar-alt fa-5x text-muted"></i>
                                </div>
                            @endif
                            <div class="carousel-overlay"></div>
                            <div class="carousel-caption text-start">
                                <span class="badge bg-primary mb-2">{{ ucfirst($event->category) }}</span>
                                <h3 class="display-4 fw-bold mb-3">{{ $event->title }}</h3>
                                
                                <p class="lead mb-4">{{ Str::limit($event->description, 150) }}</p>
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3">
                                        @if($event->user->profile_image)
                                            <img src="{{ asset($event->user->profile_image) }}" class="rounded-circle" width="40" height="40" alt="Organizer" style="object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <span class="text-primary fw-bold">{{ strtoupper(substr($event->user->name, 0, 1)) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="mb-0 small fw-medium">Organized by</p>
                                        <p class="mb-0 text-white">{{ $event->user->name }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @empty
                <div class="carousel-item active">
                    <div class="position-relative overflow-hidden">
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 500px;">
                            <div class="text-center">
                                <i class="fas fa-calendar-times fa-5x text-muted mb-3"></i>
                                <h3>No Featured Events</h3>
                                <p class="text-muted">Be the first to create an event!</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>
            
            @if($events->count() > 1)
            <button class="carousel-control-prev" type="button" data-bs-target="#featuredEventsCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#featuredEventsCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
            
            <div class="carousel-indicators">
                @foreach($events->take(5) as $key => $event)
                <button type="button" data-bs-target="#featuredEventsCarousel" data-bs-slide-to="{{ $key }}" class="{{ $key === 0 ? 'active' : '' }}"></button>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</section>

<section class="events-index py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0 animate-fade-in">Local Events</h2>
            @if(auth()->check() && auth()->user()->role_id !== 3)
            <a href="{{ route('events.create') }}" class="btn btn-primary animate-fade-in">
                <i class="fas fa-plus me-2"></i>Post New Event
            </a>
            @endif
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show animate-fade-in" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="row g-4">
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
                                <span class="badge bg-primary rounded-pill px-3 py-2 category-badge">
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
                    <h5>No events found</h5>
                    <p class="text-muted">Be the first to post an event in your area!</p>
                    <a href="{{ route('events.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Post New Event
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
</section>

<style>
.title-underline {
    width: 80px;
    height: 4px;
    background: linear-gradient(to right, #007bff, #00ff88);
    margin: 15px auto 0;
    border-radius: 2px;
}

.carousel-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to bottom, rgba(0,0,0,0.1), rgba(0,0,0,0.8));
}

.carousel-caption {
    bottom: 0;
    left: 0;
    right: auto;
    text-align: left;
    padding: 3rem;
    max-width: 800px;
    background: none;
}

.carousel-caption h3 {
    font-size: 2.5rem;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
}

.carousel-caption p {
    font-size: 1.1rem;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
}

.event-details {
    background: rgba(0,0,0,0.3);
    padding: 1rem;
    border-radius: 8px;
    backdrop-filter: blur(5px);
}

.carousel-control-prev,
.carousel-control-next {
    width: 5%;
    opacity: 0.7;
    transition: opacity 0.3s ease;
}

.carousel-control-prev:hover,
.carousel-control-next:hover {
    opacity: 1;
}

.carousel-indicators {
    position: relative;
    bottom: -20px;
    margin-bottom: 0;
}

.carousel-indicators button {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin: 0 5px;
    background-color: #666;
    border: none;
    transition: all 0.3s ease;
}

.carousel-indicators button.active {
    background-color: #007bff;
    transform: scale(1.2);
}

@media (max-width: 768px) {
    .carousel-caption {
        padding: 1.5rem;
        max-width: 100%;
    }
    
    .carousel-caption h3 {
        font-size: 1.8rem;
    }
    
    .carousel-caption p {
        font-size: 1rem;
    }
    
    .carousel-item img {
        height: 400px !important;
    }
}

.avatar {
    width: 40px;
    height: 40px;
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

/* Events Index Styles */
.events-index {
    background-color: #f8f9fa;
}

.events-index .container {
    max-width: 1200px;
}

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
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categoryFilter = document.getElementById('categoryFilter');
    const carouselItems = document.querySelectorAll('.carousel-item');
    const carousel = new bootstrap.Carousel(document.getElementById('featuredEventsCarousel'));

    categoryFilter.addEventListener('change', function() {
        const selectedCategory = this.value;
        
        carouselItems.forEach(item => {
            if (selectedCategory === '' || item.dataset.category === selectedCategory) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });

        // Reset carousel to first visible item
        const visibleItems = Array.from(carouselItems).filter(item => item.style.display !== 'none');
        if (visibleItems.length > 0) {
            const firstVisibleIndex = Array.from(carouselItems).indexOf(visibleItems[0]);
            carousel.to(firstVisibleIndex);
        }
    });
});
</script>