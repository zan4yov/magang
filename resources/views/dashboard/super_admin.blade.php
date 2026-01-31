@extends('layouts.app')

@section('title', 'Super Admin Dashboard - IGMS')

@section('content')
<div class="dashboard-container">
    <!-- Welcome Section -->
    <div class="dashboard-welcome">
        <h1 class="dashboard-title">Super Admin Dashboard</h1>
        <p class="dashboard-subtitle">Full system control and user management</p>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions" style="margin-bottom: 2rem;">
        <a href="{{ route('admin.users.index') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.5rem;">
            <svg style="width: 20px; height: 20px;" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
            </svg>
            <span>Manage Users</span>
        </a>
        <a href="{{ route('admin.users.create') }}" class="btn" style="display: inline-flex; align-items: center; gap: 0.5rem; background: #ffffff; color: #7CB342; border: 2px solid #7CB342; padding: 0.75rem 1.25rem; border-radius: 12px; text-decoration: none; font-weight: 600; transition: all 0.3s;">
            <svg style="width: 20px; height: 20px;" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
            </svg>
            <span>Create New User</span>
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="dashboard-stats">
        <!-- Total Users -->
        <div class="stat-card">
            <div class="stat-icon stat-icon-blue">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">Total Users</div>
                <div class="stat-value">{{ number_format($stats['total_users']) }}</div>
                @if($stats['growth_percentage'] > 0)
                    <div style="font-size: 0.875rem; color: #10b981; margin-top: 0.25rem;">
                        +{{ $stats['growth_percentage'] }}% from last month
                    </div>
                @endif
            </div>
        </div>

        <!-- Active Users -->
        <div class="stat-card">
            <div class="stat-icon stat-icon-green">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">Active Users (24h)</div>
                <div class="stat-value">{{ number_format($stats['active_users_24h']) }}</div>
                <div style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;">
                    Updated in last 24 hours
                </div>
            </div>
        </div>

        <!-- Users by Role -->
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                <svg fill="currentColor" viewBox="0 0 20 20">
<path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">Super Admins</div>
                <div class="stat-value">{{ number_format($stats['users_by_role']['super_admin']) }}</div>
                <div style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;">
                    {{ $stats['users_by_role']['mining_tech'] }} Mining Tech | {{ $stats['users_by_role']['user'] }} Users
                </div>
            </div>
        </div>

        <!-- New Users This Month -->
        <div class="stat-card">
            <div class="stat-icon stat-icon-purple">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">New This Month</div>
                <div class="stat-value">{{ number_format($stats['total_users_last_month']) }}</div>
                <div style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;">
                    Registered last 30 days
                </div>
            </div>
        </div>
    </div>

    <!-- Recent User Activity -->
    <div style="background: #ffffff; border-radius: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 2rem; margin-top: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="font-size: 1.25rem; font-weight: 600; color: #1f2937; margin: 0;">Recent Users</h2>
            <a href="{{ route('admin.users.index') }}" style="color: #7CB342; text-decoration: none; font-weight: 500; font-size: 0.95rem;">View All →</a>
        </div>

        <div class="activity-table-container">
            <table class="activity-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stats['recent_users'] as $recentUser)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <div class="user-avatar">{{ substr($recentUser->name, 0, 1) }}</div>
                                <span style="font-weight: 500; color: #1f2937;">{{ $recentUser->name }}</span>
                            </div>
                        </td>
                        <td>{{ $recentUser->email }}</td>
                        <td>
                            @if($recentUser->role === 'super_admin')
                                <span class="role-badge role-super-admin">Super Admin</span>
                            @elseif($recentUser->role === 'mining_tech')
                                <span class="role-badge role-mining-tech">Mining Tech</span>
                            @else
                                <span class="role-badge role-user">User</span>
                            @endif
                        </td>
                        <td>{{ $recentUser->created_at->format('d M Y') }}</td>
                        <td>
                            <div style="display: flex; gap: 0.5rem;">
                                @if($recentUser->id !== auth()->id())
                                    <a href="{{ route('admin.users.edit', $recentUser->id) }}" class="action-btn action-btn-edit" title="Edit Role">
                                        <svg fill="currentColor" viewBox="0 0 20 20" style="width: 16px; height: 16px;">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 2rem; color: #6b7280;">
                            No users found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Info Card -->
    <div class="dashboard-info">
        <div class="info-card">
            <h3 class="info-title">Super Admin Access</h3>
            <p class="info-text">
                You have full access to the system. You can manage users, assign roles, view all projects, 
                and configure system settings. Use your privileges responsibly.
            </p>
        </div>
    </div>
</div>

<style>
.quick-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

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
}

.action-btn-edit {
    background: #e8f5e9;
    color: #7CB342;
}

.action-btn-edit:hover {
    background: #7CB342;
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
