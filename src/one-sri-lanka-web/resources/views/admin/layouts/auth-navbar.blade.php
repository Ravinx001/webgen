<!-- Main navbar -->
<div class="navbar navbar-dark navbar-static py-2">
    <div class="container-fluid">
        <div class="navbar-brand">
            <a href="index" class="d-inline-flex align-items-center">
                <img src="{{URL::asset('assets/images/logo_icon.svg')}}" alt="">
                <img src="{{URL::asset('assets/images/logo_text_light.svg')}}" class="d-none d-sm-inline-block h-16px ms-3" alt="">
            </a>
        </div>

        <div class="d-flex justify-content-end align-items-center ms-auto">
            <ul class="navbar-nav flex-row">
                <li class="nav-item">
                    <a href="#" class="navbar-nav-link navbar-nav-link-icon rounded ms-1">
                        <div class="d-flex align-items-center mx-md-1">
                            <i class="ph-lifebuoy"></i>
                            <span class="d-none d-md-inline-block ms-2">Support</span>
                        </div>
                    </a>
                </li>
                @if (Route::has('register'))
                <li class="nav-item">
                    <a href="{{ route('register') }}" class="navbar-nav-link navbar-nav-link-icon rounded ms-1">
                        <div class="d-flex align-items-center mx-md-1">
                            <i class="ph-user-circle-plus"></i>
                            <span class="d-none d-md-inline-block ms-2">{{ __('Register') }}</span>
                        </div>
                    </a>
                </li>
                @endif
                @if (Route::has('login'))
                <li class="nav-item">
                    <a href="{{ route('login') }}" class="navbar-nav-link navbar-nav-link-icon rounded ms-1">
                        <div class="d-flex align-items-center mx-md-1">
                            <i class="ph-user-circle"></i>
                            <span class="d-none d-md-inline-block ms-2">{{ __('Login') }}</span>
                        </div>
                    </a>
                </li>
                @endif
            </ul>
        </div>
    </div>
</div>
<!-- /main navbar -->
