@extends('layouts.auth')

@section('title', 'Verify Code - IGMS')

@section('content')
<div class="auth-container">
    <div class="auth-background">
        <div class="gradient-orb orb-1"></div>
        <div class="gradient-orb orb-2"></div>
        <div class="gradient-orb orb-3"></div>
    </div>

    <div class="auth-card">
        <div class="auth-header">
            <h1 class="auth-title">Verify Code</h1>
            <p class="auth-subtitle">Enter the verification code sent to your email</p>
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

        <form method="POST" action="{{ route('password.verify') }}" class="auth-form">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ session('email') ?? old('email') }}"
                    class="form-input @error('email') input-error @enderror"
                    placeholder="you@example.com"
                    required
                    readonly
                >
                @error('email')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="code" class="form-label">Verification Code</label>
                <input 
                    type="text" 
                    id="code" 
                    name="code" 
                    value="{{ old('code') }}"
                    class="form-input code-input @error('code') input-error @enderror"
                    placeholder="Enter 6-digit code"
                    maxlength="6"
                    required
                    autofocus
                >
                @error('code')
                    <span class="form-error">{{ $message }}</span>
                @enderror
                <p class="form-hint">Check your email for the 6-digit verification code (expires in 15 minutes)</p>
            </div>

            <button type="submit" class="btn btn-primary btn-block">
                <span>Verify Code</span>
                <svg class="btn-icon" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
            </button>
        </form>

        <div class="auth-footer">
            <p class="footer-text">
                Didn't receive the code? 
                <a href="{{ route('password.forgot') }}" class="footer-link">Request a new one</a>
            </p>
        </div>
    </div>
</div>

<style>
.code-input {
    text-align: center;
    font-size: 1.5rem;
    letter-spacing: 0.5rem;
    font-weight: 600;
}
</style>
@endsection
