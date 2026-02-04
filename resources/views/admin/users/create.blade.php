@extends('layouts.app')

@section('title', 'Create New User - IGMS')

@section('content')
<div class="dashboard-container">
    <!-- Header -->
    <div style="margin-bottom: 2rem;">
        <a href="{{ route('admin.users.index') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; color: #6b7280; text-decoration: none; margin-bottom: 1rem; font-size: 0.95rem;">
            <svg style="width: 18px; height: 18px;" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
            </svg>
            <span>Back to Users</span>
        </a>
        <h1 class="dashboard-title">Create New User</h1>
        <p class="dashboard-subtitle">Add a new user to the system</p>
    </div>

    <div style="max-width: 600px;">
        <div style="background: #ffffff; border-radius: 16px; padding: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            @if($errors->any())
                <div style="margin-bottom: 1.5rem; background: #fee2e2; border: 1px solid #ef4444; border-radius: 8px; padding: 1rem; color: #991b1b;">
                    <ul style="margin: 0; padding-left: 1.25rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="name" style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Full Name</label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name') }}"
                        class="form-input @error('name') input-error @enderror"
                        style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem;"
                        required
                        autofocus
                    >
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="email" style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        class="form-input @error('email') input-error @enderror"
                        style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem;"
                        required
                    >
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="role" style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Role</label>
                    <select 
                        id="role" 
                        name="role" 
                        class="form-input form-select"
                        style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem;"
                        required
                    >
                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                        <option value="mining_tech" {{ old('role') == 'mining_tech' ? 'selected' : '' }}>Mining Technology Team</option>
                        <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="password" style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-input @error('password') input-error @enderror"
                        style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem;"
                        required
                    >
                    <p style="font-size: 0.75rem; color: #6b7280; margin-top: 0.5rem;">Minimum 8 characters</p>
                </div>

                <div class="form-group" style="margin-bottom: 2rem;">
                    <label for="password_confirmation" style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Confirm Password</label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        class="form-input"
                        style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem;"
                        required
                    >
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary">
                        <svg style="width: 18px; height: 18px;" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
                        </svg>
                        <span>Create User</span>
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn" style="background: #e5e7eb; color: #374151;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
