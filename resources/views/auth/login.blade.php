@extends('layouts.app')

@section('content')

<div class="login-wrapper">

    <!-- KotoBee Title -->
    <h1 class="login-title">KotoBee</h1>

    <!-- Bee Illustration -->
    <img src="{{ asset('storage/images/commons/bee_flower.svg') }}" 
         alt="Bee" 
         class="login-bee">

    <!-- Mountain Background -->
    <div class="login-mountain-wrapper">
        <img src="{{ asset('storage/images/commons/mountain.svg') }}" 
             alt="Mountain" 
             class="login-mountain">

        <!-- Login Form Floating on Mountain -->
        <div class="login-form-card">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <h2 class="login-form-title">Login</h2>

                <!-- Email -->
                <div class="mb-1">
                    <label for="email" class="form-label">Email Address</label>
                    <input id="email" type="email" 
                        class="form-control @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email') }}" required autofocus placeholder="example@example.com">

                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" 
                        class="form-control @error('password') is-invalid @enderror"
                        name="password" required>

                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit -->
                <button type="submit" class="btn btn-primary w-100 kb-login-btn">
                    Login
                </button>

                @if (Route::has('password.request'))
                    <a class="btn btn-link w-100 mt-2 text-center" 
                       href="{{ route('password.request') }}">
                        Forgot Your Password?
                    </a>
                @endif

                {{-- register --}}
                <div class="text-center mt-3">
                    <a class="btn btn-link w-100 text-center" href="{{ route('register') }}">
                        Create a new account
                    </a>
                </div>
            </form>
        </div>
    </div>

</div>

@endsection
