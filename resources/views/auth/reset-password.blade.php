@extends('layouts.auth')

@section('title', 'Reset Password - IGMS')

@section('content')
<div class="auth-container">
    <div class="auth-background">
        <div class="gradient-orb orb-1"></div>
        <div class="gradient-orb orb-2"></div>
        <div class="gradient-orb orb-3"></div>
    </div>

    <div class="auth-card">
        <div class="auth-header">
            <h1 class="auth-title">Reset Password</h1>
            <p class="auth-subtitle">Enter your new password</p>
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

        <form method="POST" action="{{ route('password.reset') }}" class="auth-form">
            @csrf

            <input type="hidden" name="email" value="{{ session('email') }}">
            <input type="hidden" name="code" value="{{ session('code') }}">

            <div class="form-row">
                <div class="form-group">
                    <label for="password" class="form-label">New Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-input @error('password') input-error @enderror"
                        placeholder="••••••••"
                        required
                        autofocus
                    >
                    @error('password')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                    <p class="form-hint">Minimum 8 characters</p>
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        class="form-input"
                        placeholder="••••••••"
                        required
                    >
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block">
                <span>Reset Password</span>
                <svg class="btn-icon" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
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
@endsection
