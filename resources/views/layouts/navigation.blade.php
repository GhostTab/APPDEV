<div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
    <a class="dropdown-item" href="{{ route('profile.edit') }}">
        {{ __('Profile') }}
    </a>
    
    @if(!(auth()->user()->role_id === 1) && !(auth()->user()->role_id === 2))
        <a class="dropdown-item" href="{{ route('organizer.apply') }}">
            {{ __('Become an Organizer') }}
        </a>
    @endif
    
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <a class="dropdown-item" href="{{ route('logout') }}"
           onclick="event.preventDefault();
           this.closest('form').submit();">
            {{ __('Log Out') }}
        </a>
    </form>
</div> 