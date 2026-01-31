@extends('layouts.auth')

@section('title', 'Login - IGMS')

@section('content')
<div class="auth-container">
    <div class="auth-background">
        <div class="gradient-orb orb-1"></div>
        <div class="gradient-orb orb-2"></div>
        <div class="gradient-orb orb-3"></div>
    </div>

    <div class="auth-card">
        <div class="auth-header">
            <h1 class="auth-title">Welcome Back</h1>
            <p class="auth-subtitle">Sign in to your IGMS account</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <ul class="error-list">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="auth-form">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}"
                    class="form-input @error('email') input-error @enderror"
                    placeholder="you@example.com"
                    required 
                    autofocus
                >
                @error('email')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="form-input @error('password') input-error @enderror"
                    placeholder="••••••••"
                    required
                >
                @error('password')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-options">
                <label class="checkbox-label">
                    <input type="checkbox" name="remember" class="checkbox-input">
                    <span class="checkbox-text">Remember me</span>
                </label>
                <a href="{{ route('password.forgot') }}" class="forgot-password-link">Forgot password?</a>
            </div>

            <button type="submit" class="btn btn-primary btn-block">
                <span>Sign In</span>
                <svg class="btn-icon" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </form>

        <div class="auth-footer">
            <p class="footer-text">
                Don't have an account? 
                <a href="{{ route('register') }}" class="footer-link">Create one now</a>
            </p>
        </div>
    </div>
</div>
@endsection
