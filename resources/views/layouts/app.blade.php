<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard - IGMS')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/auth.css'])
</head>
<body>
    <div class="app-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <!-- Logo -->
            <div class="sidebar-header">
                <div class="sidebar-logo">IGMS</div>
            </div>

            <!-- User Info -->
            <div class="sidebar-user">
                <div class="sidebar-user-avatar">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="sidebar-user-info">
                    <div class="sidebar-user-name">{{ Auth::user()->name }}</div>
                    <div class="sidebar-user-role">
                        @if(Auth::user()->role === 'super_admin')
                            Super Admin
                        @elseif(Auth::user()->role === 'mining_tech')
                            Mining Tech
                        @else
                            User
                        @endif
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="sidebar-nav">
                <!-- Search Bar -->
                <div class="sidebar-search">
                    <input type="text" placeholder="Search..." class="sidebar-search-input">
                    <svg class="sidebar-search-icon" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                    </svg>
                </div>

                <a href="{{ route('dashboard') }}" class="sidebar-nav-item {{ request()->routeIs('dashboard') || request()->routeIs('projects.index') ? 'active' : '' }}">
                    <svg class="sidebar-nav-icon" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                    </svg>
                    <span>Dashboard</span>
                </a>

                @if(!Auth::user()->isSuperAdmin())
                    <a href="{{ route('projects.drafts') }}" class="sidebar-nav-item {{ request()->routeIs('projects.drafts') ? 'active' : '' }}">
                        <svg class="sidebar-nav-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                        </svg>
                        <span>Drafts</span>
                        @if(isset($draftsCount) && $draftsCount > 0)
                            <span class="sidebar-badge">{{ $draftsCount }}</span>
                        @endif
                    </a>

                    <a href="{{ route('projects.trash') }}" class="sidebar-nav-item {{ request()->routeIs('projects.trash') ? 'active' : '' }}">
                        <svg class="sidebar-nav-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span>Trash</span>
                        @if(isset($trashedCount) && $trashedCount > 0)
                            <span class="sidebar-badge">{{ $trashedCount }}</span>
                        @endif
                    </a>
                @endif

                @if(Auth::user()->isSuperAdmin())
                    <a href="{{ route('admin.users.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <svg class="sidebar-nav-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                        <span>User Management</span>
                    </a>
                @endif

                <!-- Starred Section -->
                @if(!Auth::user()->isSuperAdmin())
                    <div class="sidebar-section-header">STARRED</div>
                    <a href="{{ route('projects.starred') }}" class="sidebar-nav-item {{ request()->routeIs('projects.starred') ? 'active' : '' }}">
                        <svg class="sidebar-nav-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <span>Team Project</span>
                        @if(isset($starredCount) && $starredCount > 0)
                            <span class="sidebar-badge">{{ $starredCount }}</span>
                        @endif
                    </a>
                @endif
            </nav>

            <!-- Logout Button -->
            <div class="sidebar-footer">
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="sidebar-logout-btn">
                        <svg class="sidebar-nav-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"/>
                        </svg>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Bar (optional, for search or notifications) -->
            <div class="top-bar">
                <div class="top-bar-title">
                    @yield('title', 'Dashboard')
                </div>
                <div class="top-bar-actions">
                    <!-- Future: search, notifications, etc -->
                </div>
            </div>

            <!-- Page Content -->
            <div class="content-wrapper">
                <!-- Flash Messages -->
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

                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
