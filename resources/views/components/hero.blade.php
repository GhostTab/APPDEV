@extends('layouts.app')

@section('content')
<div class="position-relative" style="font-family: 'Poppins', sans-serif;">
    <!-- Hero Section -->
    <div class="vh-100 position-relative">
        <!-- Background Overlay -->
        <div class="position-absolute w-100 h-100" style="
            background: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)),
            url('{{ asset('images/San.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: 0;
        "></div>

        <div class="position-relative" style="z-index: 2;">
            @include('components.navbar')
        </div>

        <!-- Hero Content -->
        <div class="container h-100 d-flex flex-column justify-content-center align-items-center text-white text-center position-relative" style="z-index: 1; margin-top: -100px;">
            <h1 class="display-4 fw-bold mb-0">Discover Tacloban's Vibrant Events</h1>
            <p class="lead mb-5">Fun and join exciting events in Tacloban City or create your own to share with the community.</p>
            
            <!-- Search Bar -->
            <div class="w-75 max-width-800">
                <form action="{{ route('home') }}" method="GET" class="w-100">
                    <div class="input-group input-group-lg">
                        <input type="text" 
                            name="search"
                            class="form-control border-0" 
                            placeholder="Search events..." 
                            value="{{ request('search') }}"
                            style="font-family: 'Poppins', sans-serif;">
                        <button class="btn btn-light" type="submit" style="font-family: 'Poppins', sans-serif;">
                            <i class="bi bi-search"></i>
                            Search
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('components.featured-events', ['events' => $events])
</div>
@endsection
