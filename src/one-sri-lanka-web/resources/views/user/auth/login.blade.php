@extends('user.layouts.app')

@section('title', 'One Sri Lanka Login')

@section('content')
  <div style="display:flex; justify-content:center; flex-direction:column; align-items:center;">

    <!-- Moving Text Section -->
    <div
    style="width:100%; max-width:480px; overflow:hidden; background-color:#0013ff; border-radius:12px; padding:0.8rem 1rem; margin-bottom:20px;">
    <div class="scrolling-text" style="
      display:inline-block;
      white-space:nowrap;
      color:#ffffff;
      font-weight:600;
      font-size:1rem;
      animation: scroll-left 15s linear infinite;
      ">

    </div>
    </div>

    <!-- Login Card (sizes unchanged) -->

    <div
    style="display: flex; justify-content: center; align-items: center; background-color: #d0d3db; padding: 2rem 1rem;">
    <div class="login-container" style="
      width: 100%;
      max-width: 540px;       /* increased from 480px */
      background-color: #f8f9fa;
      padding: 3rem 3.5rem;   /* slightly more padding */
      border-radius: 16px;
      box-shadow: 0 8px 24px rgba(52, 58, 64, 0.2);
      transition: box-shadow 0.3s ease;  /* remove height scaling */
      box-sizing: border-box;
      overflow: visible;
    ">
      <h2 class="text-center mb-4" style="color:#0000ff; font-weight: 600; font-size: 2rem;">
      One Sri Lanka Login
      </h2>

      <!-- Error space reserved -->

      @if ($errors->has('global'))      <div style="min-height: 40px; margin-bottom: 1rem;">
      <div class="alert alert-danger" style="border-radius: 8px;">
      {{ $errors->first('global') }}
      </div>    </div>
    @endif
  

      <form action="{{ route('login.post') }}" method="POST">
      @csrf
      <div class="form-group mb-4">
        <label for="username" style="color:#495057; font-weight: 500;">Username</label>
        <input value="{{ old('user_name') }}" name="user_name" type="text" id="username" placeholder="Enter username"
        required class="form-control @error('user_name') is-invalid @enderror"
        style="background-color: #e9ecef; color: #0000ff; border-radius: 8px; border: 1px solid #ced4da;" />
        @error('user_name')
      <div class="text-danger" style="font-size: 0.85rem;">{{ $message }}</div>
      @enderror
      </div>

      <div class="form-group mb-4">
        <label for="password" style="color:#495057; font-weight: 500;">Password</label>
        <input name="password" type="password" id="password" placeholder="Enter password" required
        class="form-control @error('password') is-invalid @enderror"
        style="background-color: #e9ecef; color: #0000ff; border-radius: 8px; border: 1px solid #ced4da;" />
        @error('password')
      <div class="text-danger" style="font-size: 0.85rem;">{{ $message }}</div>
      @enderror
      </div>

      <button type="submit" class="btn btn-blue w-100"
        style="background-color: #0013ff; border-radius: 15px; font-weight: 600; padding: 1rem;">
        Login
      </button>
      <p class="footer-text text-center mt-3" style="color: #6c757d; font-size: 0.9rem;">
        Don't have an account? Contact admin.
      </p>
      </form>
    </div>
    </div>

  </div>

  <style>
    @keyframes scroll-left {
    0% {
      transform: translateX(100%);
    }

    100% {
      transform: translateX(-100%);
    }
    }
  </style>
@endsection