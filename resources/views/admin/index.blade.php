@extends('layouts.app')

@section('title', 'User Management - IGMS')

@section('content')
<div class="dashboard-container">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 class="dashboard-title">User Management</h1>
            <p class="dashboard-subtitle">Manage system users and roles</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.5rem;">
            <svg style="width: 20px; height: 20px;" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
            </svg>
            <span>Create New User</span>
        </a>
    </div>

   <!-- Filters -->
    <div style="background: #ffffff; border-radius: 16px; padding: 1.5rem; margin-bottom: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <form method="GET" action="{{ route('admin.users.index') }}">
            <div style="display: grid; grid-template-columns: 1fr 200px auto; gap: 1rem; align-items: end;">
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Search</label>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Search by name or email..."
                        class="form-input"
                        style="width: 100%;"
                    >
                </div>
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Role Filter</label>
                    <select name="role" class="form-input form-select">
                        <option value="">All Roles</option>
                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                        <option value="mining_tech" {{ request('role') == 'mining_tech' ? 'selected' : '' }}>Mining Tech</option>
                        <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    </select>
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" class="btn btn-primary">
                        <svg style="width: 18px; height: 18px;" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                    @if(request('search') || request('role'))
                        <a href="{{ route('admin.users.index') }}" class="btn" style="background: #e5e7eb; color: #374151;">Clear</a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div style="background: #ffffff; border-radius: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
        <div class="activity-table-container">
            <table class="activity-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Joined</th>
                        <th>Last Active</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <div class="user-avatar">{{ substr($user->name, 0, 1) }}</div>
                                <div>
                                    <div style="font-weight: 500; color: #1f2937;">{{ $user->name }}</div>
                                    @if($user->id === auth()->id())
                                        <span style="font-size: 0.75rem; color: #7CB342;">(You)</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->role === 'super_admin')
                                <span class="role-badge role-super-admin">Super Admin</span>
                            @elseif($user->role === 'mining_tech')
                                <span class="role-badge role-mining-tech">Mining Tech</span>
                            @else
                                <span class="role-badge role-user">User</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('d M Y') }}</td>
                        <td>{{ $user->updated_at->diffForHumans() }}</td>
                        <td>
                            <div style="display: flex; gap: 0.5rem;">
                                @if($user->id !== auth()->id())
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="action-btn action-btn-edit" title="Edit Role">
                                        <svg fill="currentColor" viewBox="0 0 20 20" style="width: 16px; height: 16px;">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.users.reset-password', $user->id) }}" class="action-btn action-btn-reset" title="Reset Password">
                                        <svg fill="currentColor" viewBox="0 0 20 20" style="width: 16px; height: 16px;">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn action-btn-delete" title="Delete User">
                                            <svg fill="currentColor" viewBox="0 0 20 20" style="width: 16px; height: 16px;">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    </form>
                                @else
                                    <span style="font-size: 0.875rem; color: #9ca3af;">-</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 3rem; color: #6b7280;">
                            <svg style="width: 48px; height: 48px; margin: 0 auto 1rem; opacity: 0.5;" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                            </svg>
                            <div style="font-weight: 500; margin-bottom: 0.5rem;">No users found</div>
                            <div style="font-size: 0.875rem;">Try adjusting your search or filter</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div style="padding: 1.5rem; border-top: 1px solid #e5e7eb;">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>

<style>
.activity-table-container {
    overflow-x: auto;
}

.activity-table {
    width: 100%;
    border-collapse: collapse;
}

.activity-table thead {
    background: #f9fafb;
}

.activity-table th {
    padding: 0.75rem 1rem;
    text-align: left;
    font-size: 0.875rem;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.activity-table td {
    padding: 1rem;
    border-top: 1px solid #e5e7eb;
    font-size: 0.95rem;
    color: #4b5563;
}

.activity-table tbody tr {
    transition: background 0.2s;
}

.activity-table tbody tr:hover {
    background: #f9fafb;
}

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
    flex-shrink: 0;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    transition: all 0.2s;
    cursor: pointer;
    text-decoration: none;
    border: none;
}

.action-btn-edit {
    background: #e8f5e9;
    color: #7CB342;
}

.action-btn-edit:hover {
    background: #7CB342;
    color: #ffffff;
}

.action-btn-reset {
    background: #fff3e0;
    color: #ef6c00;
}

.action-btn-reset:hover {
    background: #ef6c00;
    color: #ffffff;
}

.action-btn-delete {
    background: #fee2e2;
    color: #ef4444;
}

.action-btn-delete:hover {
    background: #ef4444;
    color: #ffffff;
}
</style>
@endsection
