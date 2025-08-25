@extends('layouts.master-login')

@section('content')

<!-- Content area -->
<div class="content d-flex justify-content-center align-items-center">

    <!-- Registration form -->
    <form class="login-form" method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
        @csrf
        <div class="card mb-0">
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="d-inline-flex align-items-center justify-content-center mb-4 mt-2">
                        <img src="{{URL::asset('assets/images/logo_icon.svg')}}" class="h-48px" alt="">
                    </div>
                    <h5 class="mb-0">Create account</h5>
                    <span class="d-block text-muted">All fields are required</span>
                </div>

                <div class="text-center text-muted content-divider mb-3">
                    <span class="px-2">Your credentials</span>
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">{{ __('Name') }}</label>
                    <div class="form-control-feedback form-control-feedback-start">
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="John Doe">
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <i class="ph-x-circle me-1"></i>
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        <div class="form-control-feedback-icon">
                            <i class="ph-user-circle text-muted"></i>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">{{ __('Email Address') }}</label>
                    <div class="form-control-feedback form-control-feedback-start">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="john@doe.com">
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <i class="ph-x-circle me-1"></i>
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        <div class="form-control-feedback-icon">
                            <i class="ph-at text-muted"></i>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">{{ __('Password') }}</label>
                    <div class="form-control-feedback form-control-feedback-start">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="•••••••••••">
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <i class="ph-x-circle me-1"></i>
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        <div class="form-control-feedback-icon">
                            <i class="ph-lock text-muted"></i>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
                    <div class="form-control-feedback form-control-feedback-start">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="•••••••••••">
                        <div class="form-control-feedback-icon">
                            <i class="ph-lock text-muted"></i>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="input-avatar" class="form-label">{{ __('Avatar') }}</label>
                    <input type="file" class="form-control @error('avatar') is-invalid @enderror" name="avatar" id="input-avatar" required>
                    @error('avatar')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    <div class="">
                        <i data-feather="file"></i>
                    </div>
                </div>

                <button type="submit" class="btn btn-teal w-100"> {{ __('Register') }}</button>
            </div>
        </div>
    </form>
    <!-- /registration form -->

</div>
<!-- /content area -->

@endsection
