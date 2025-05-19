<nav class="navbar navbar-dark py-2">
    <div class="container" style="max-width: 95%;">
        <div class="d-flex justify-content-between align-items-center w-100">
            <a href="" class="navbar-brand fw-semibold d-flex align-items-center hover-effect" style="font-family: 'Poppins', sans-serif;">
                <span class="text-danger me-2">❤</span> Tacloban Events Hub
            </a>
            <div class="d-flex align-items-center">
                <a href="{{ route('home') }}" class="text-decoration-none text-white fw-semibold me-4 hover-effect" style="font-family: 'Poppins', sans-serif;">Home</a>
                <a href="{{ route('events.index') }}" class="text-decoration-none text-white fw-semibold me-4 hover-effect" style="font-family: 'Poppins', sans-serif;">Events</a>
                <a href="{{ route('events.past') }}" class="text-decoration-none text-white fw-semibold me-4 hover-effect" style="font-family: 'Poppins', sans-serif;">Past Events</a>
                @auth
                    @if(Auth::user()->role_id === 2)
                        <a href="{{ route('events.create') }}" class="btn btn-primary fw-semibold me-4 hover-effect" style="font-family: 'Poppins', sans-serif;">
                            <i class="fas fa-plus-circle me-2"></i>Post Event
                        </a>
                    @endif
                    <!-- Notification Dropdown -->
                    <div class="dropdown me-4">
                        <a class="nav-link text-white hover-effect position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" id="notificationDropdown">
                            <i class="fas fa-bell"></i>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge">
                                    {{ auth()->user()->unreadNotifications->count() }}
                                </span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end notification-dropdown">
                            <li><h6 class="dropdown-header">Notifications</h6></li>
                            @forelse(auth()->user()->notifications()->latest()->take(5)->get() as $notification)
                                <li>
                                    <a class="dropdown-item {{ !$notification->read_at ? 'bg-light' : '' }}" 
                                       href="{{ isset($notification->data['event_id']) ? route('events.show', $notification->data['event_id']) : route('profile') }}">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                @if(isset($notification->data['event_id']))
                                                    <i class="fas fa-calendar-check text-primary"></i>
                                                @else
                                                    <i class="fas fa-user-check {{ $notification->data['status'] === 'approved' ? 'text-success' : 'text-danger' }}"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="mb-0">{{ $notification->data['message'] }}</p>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @empty
                                <li><p class="dropdown-item text-muted mb-0">No notifications</p></li>
                            @endforelse
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-center" href="#">View all</a></li>
                        </ul>
                    </div>
                    <!-- Profile Dropdown -->
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle text-white hover-effect" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            @if(Auth::user()->profile_image)
                                <img src="{{ asset(Auth::user()->profile_image) }}" class="rounded-circle profile-image" width="40" height="40" alt="Profile">
                            @else
                                <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center profile-image" style="width: 40px; height: 40px;">
                                    <span class="text-primary fw-bold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                </div>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end animate">
                            <li>
                                <a class="dropdown-item hover-effect" href="{{ route('profile') }}">
                                    <i class="fas fa-user me-2"></i>Profile
                                </a>
                            </li>
                            @if(Auth::user()->role_id === 2)
                                <li>
                                    <a class="dropdown-item hover-effect" href="{{ route('events.manage') }}">
                                        <i class="fas fa-calendar-alt me-2"></i>Manage Events
                                    </a>
                                </li>
                            @elseif(Auth::user()->role_id === 3)
                                <li>
                                    <a class="dropdown-item hover-effect" href="{{ route('organizer.apply') }}">
                                        <i class="fas fa-user-plus me-2"></i>Apply as Organizer
                                    </a>
                                </li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item hover-effect">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-decoration-none text-white fw-semibold me-4 hover-effect">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-light rounded-3 fw-semibold px-4 hover-effect">Register</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<style>
    .hover-effect {
        transition: all 0.3s ease;
    }
    
    .hover-effect:hover {
        opacity: 0.8;
        transform: translateY(-2px);
    }
    
    .dropdown-menu {
        margin-top: 0.5rem;
        transition: all 0.2s ease-in-out;
        transform: translateY(10px);
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        border: none;
        padding: 8px;
        background: white;
    }
    
    .show > .dropdown-menu {
        transform: translateY(0);
    }
    
    .profile-image {
        transition: transform 0.3s ease;
    }
    
    .profile-image:hover {
        transform: scale(1.1);
    }
    
    .dropdown-item {
        transition: all 0.2s ease;
        padding: 10px 16px;
        border-radius: 8px;
        margin: 2px 0;
        color: #1a1a1a;
    }
    
    .dropdown-item:hover {
        background: linear-gradient(145deg, #e8f0fe, #f0f7ff);
        color: #0d6efd;
        transform: none;
    }
    
    .dropdown-item i {
        margin-right: 8px;
        color: #0d6efd;
    }
    
    .dropdown-divider {
        margin: 8px 0;
        border-color: #f0f0f0;
    }
    
    .btn-primary {
        background-color: #0d6efd;
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
    }
    
    .btn-primary:hover {
        background-color: #0A2F86FF;
    }
</style>

<!-- Add this script at the bottom of the file -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const notificationDropdown = document.getElementById('notificationDropdown');
    
    notificationDropdown.addEventListener('click', function() {
        const badge = document.querySelector('.notification-badge');
        if (badge) {
            fetch('{{ route("notifications.markAsRead") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    badge.remove();
                }
            })
            .catch(error => console.error('Error:', error));
        }
    });
});
</script>
