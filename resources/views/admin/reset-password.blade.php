@extends('layouts.app')

@section('title', 'Reset Password - User Management')

@section('content')
<div class="dashboard-container">
    <div style="margin-bottom: 2rem;">
        <a href="{{ route('admin.users.index') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; color: #6b7280; text-decoration: none; margin-bottom: 1rem; font-size: 0.95rem;">
            <svg style="width: 18px; height: 18px;" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
            </svg>
            <span>Back to Users</span>
        </a>
        <h1 class="dashboard-title">Reset Password</h1>
        <p class="dashboard-subtitle">Set a new password for {{ $user->name }}</p>
    </div>

    <div style="max-width: 600px;">
        <div style="background: #ffffff; border-radius: 16px; padding: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <!-- User Info -->
            <div style="background: #f9fafb; border-radius: 12px; padding: 1.25rem; margin-bottom: 2rem; border-left: 4px solid #7CB342;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 48px; height: 48px; border-radius: 50%; background: linear-gradient(135deg, #7CB342 0%, #558B2F 100%); color: #ffffff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.25rem;">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div>
                        <div style="font-weight: 600; color: #1f2937; font-size: 1.125rem;">{{ $user->name }}</div>
                        <div style="color: #6b7280; font-size: 0.875rem;">{{ $user->email }}</div>
                        <div style="margin-top: 0.25rem;">
                            <span style="background: #e0f2f1; color: #00695c; padding: 0.25rem 0.5rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">
                                {{ str_replace('_', ' ', $user->role) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Warning Alert -->
            <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                <div style="display: flex; gap: 0.75rem;">
                    <svg style="width: 20px; height: 20px; color: #ff9800; flex-shrink: 0; margin-top: 2px;" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <div style="font-weight: 600; color: #e65100; margin-bottom: 0.25rem;">Password Reset Warning</div>
                        <div style="color: #ef6c00; font-size: 0.875rem;">
                            This will immediately change the user's password. They will need to use the new password to log in.
                        </div>
                    </div>
                </div>
            </div>

            @if($errors->any())
                <div class="alert alert-error" style="margin-bottom: 1.5rem;">
                    <ul class="error-list">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.users.update-password', $user->id) }}">
                @csrf

                <div class="form-group">
                    <label for="password" class="form-label">New Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-input @error('password') input-error @enderror"
                        placeholder="Enter new password (minimum 8 characters)"
                        required
                        autofocus
                    >
                    @error('password')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                    <p class="form-hint">Password must be at least 8 characters long</p>
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        class="form-input @error('password_confirmation') input-error @enderror"
                        placeholder="Confirm new password"
                        required
                    >
                    @error('password_confirmation')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary">
                        <svg style="width: 18px; height: 18px;" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                        </svg>
                        <span>Reset Password</span>
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn" style="background: #e5e7eb; color: #374151;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
