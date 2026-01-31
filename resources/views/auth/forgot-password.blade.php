@extends('layouts.auth')

@section('title', 'Forgot Password - IGMS')

@section('content')
<div class="auth-container">
    <div class="auth-background">
        <div class="gradient-orb orb-1"></div>
        <div class="gradient-orb orb-2"></div>
        <div class="gradient-orb orb-3"></div>
    </div>

    <div class="auth-card">
        <div class="auth-header">
            <h1 class="auth-title">Forgot Password?</h1>
            <p class="auth-subtitle">Enter your email to receive a verification code</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
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

        <form method="POST" action="{{ route('password.email') }}" class="auth-form">
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
                <p class="form-hint">We'll send a 6-digit verification code to this email</p>
            </div>

            <button type="submit" class="btn btn-primary btn-block">
                <span>Send Verification Code</span>
                <svg class="btn-icon" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                </svg>
            </button>
        </form>

        <div class="auth-footer">
            <p class="footer-text">
                Remember your password? 
                <a href="{{ route('login') }}" class="footer-link">Back to login</a>
            </p>
        </div>
    </div>
</div>
@endsection
