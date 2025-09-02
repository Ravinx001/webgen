<nav class="navbar navbar-custom px-3">
    <a href="{{ route('dashboard') }}" class="navbar-brand">ONE SRI LANKA</a>
    <div class="dropdown ms-auto">
        <div class="profile-circle" id="profileDropdown" data-bs-toggle="dropdown"></div>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
            {{-- <li><a class="dropdown-item" href="{{ route('userProfile') }}">My Profile</a></li>
            <li><a class="dropdown-item" href="#">Settings</a></li>
            <li>
                <hr class="dropdown-divider">
            </li> --}}
            <li><a class="dropdown-item text-danger" href="#">Logout</a></li>
        </ul>
    </div>
</nav>
