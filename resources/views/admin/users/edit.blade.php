@extends('layouts.app')

@section('title', 'Edit User Role - IGMS')

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
        <h1 class="dashboard-title">Edit User Role</h1>
        <p class="dashboard-subtitle">Change {{ $user->name }}'s role</p>
    </div>

    <div style="max-width: 600px;">
        <div style="background: #ffffff; border-radius: 16px; padding: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <!-- User Info -->
            <div style="background: #f9fafb; border-radius: 12px; padding: 1.5rem; margin-bottom: 2rem;">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                    <div class="user-avatar" style="width: 60px; height: 60px; font-size: 1.5rem;">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div>
                        <div style="font-weight: 600; font-size: 1.125rem; color: #1f2937;">{{ $user->name }}</div>
                        <div style="color: #6b7280; font-size: 0.95rem;">{{ $user->email }}</div>
                    </div>
                </div>
                <div style="display: flex; gap: 1rem; font-size: 0.875rem; color: #6b7280;">
                    <div>
                        <span style="font-weight: 500;">Joined:</span> {{ $user->created_at->format('d M Y') }}
                    </div>
                    <div>
                        <span style="font-weight: 500;">Status:</span> 
                        @if($user->isOnline())
                            <span style="color: #10b981;">Online</span>
                        @else
                            <span>Offline</span>
                        @endif
                    </div>
                </div>
            </div>

            @if($errors->any())
                <div class="alert alert-error" style="margin-bottom: 1.5rem; background: #fee2e2; border: 1px solid #ef4444; border-radius: 8px; padding: 1rem; color: #991b1b;">
                    <ul style="margin: 0; padding-left: 1.25rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                @csrf
                @method('PUT')

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="role" style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">User Role</label>
                    <select 
                        id="role" 
                        name="role" 
                        class="form-input form-select"
                        style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem;"
                        required
                    >
                        <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                        <option value="mining_tech" {{ $user->role == 'mining_tech' ? 'selected' : '' }}>Mining Technology Team</option>
                        <option value="super_admin" {{ $user->role == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    </select>
                    <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.5rem;">
                        Current role: 
                        @if($user->role === 'super_admin')
                            <strong style="color: #C2185B;">Super Admin</strong>
                        @elseif($user->role === 'mining_tech')
                            <strong style="color: #558B2F;">Mining Technology Team</strong>
                        @else
                            <strong style="color: #1976D2;">User</strong>
                        @endif
                    </p>
                </div>

                <div style="background: #fef3c7; border: 1px solid #fbbf24; border-radius: 12px; padding: 1rem; margin-bottom: 1.5rem;">
                    <div style="display: flex; align-items: start; gap: 0.75rem;">
                        <svg style="width: 20px; height: 20px; color: #f59e0b; flex-shrink: 0; margin-top: 0.125rem;" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div style="font-size: 0.875rem; color: #92400e;">
                            <strong>Warning:</strong> Changing a user's role will immediately affect their system access and permissions.
                        </div>
                    </div>
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary">
                        <svg style="width: 18px; height: 18px;" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span>Update Role</span>
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn" style="background: #e5e7eb; color: #374151;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #7CB342 0%, #558B2F 100%);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1rem;
}
</style>
@endsection
