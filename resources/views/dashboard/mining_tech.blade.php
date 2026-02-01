@extends('layouts.app')

@section('title', 'Mining Technology Team Dashboard - IGMS')

@section('content')
<div class="mining-tech-dashboard">
    <!-- Header -->
    <div class="dashboard-header">
        <div>
            <h1 class="dashboard-title">Mining Technology Dashboard</h1>
            <p class="dashboard-subtitle">Welcome, {{ $user->name }}!</p>
        </div>
        <a href="{{ route('projects.create') }}" class="btn-primary">
            <svg fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
            </svg>
            New Project
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: #e8f5e9;">
                <svg style="color: #4caf50;" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">Total Projects</div>
                <div class="stat-value">{{ $stats['total_projects'] }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: #e3f2fd;">
                <svg style="color: #2196f3;" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">My Projects</div>
                <div class="stat-value">{{ $stats['my_projects'] }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: #fff3e0;">
                <svg style="color: #ff9800;" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">Recent (7 days)</div>
                <div class="stat-value">{{ $stats['recent_projects'] }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: #f3e5f5;">
                <svg style="color: #9c27b0;" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">By Category</div>
                <div class="stat-breakdown">
                    <span>Mining: {{ $stats['by_category']['mine_tech'] }}</span>
                    <span>Enviro: {{ $stats['by_category']['enviro'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="dashboard-tabs">
        <button class="tab-btn active" data-tab="all-projects">
            <svg fill="currentColor" viewBox="0 0 20 20">
                <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
            </svg>
            All Projects
        </button>
        <button class="tab-btn" data-tab="my-projects">
            <svg fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
            </svg>
            My Projects
        </button>
    </div>

    <!-- Search and Filter -->
    <div class="toolbar">
        <div class="search-box">
            <svg fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
            </svg>
            <input type="text" id="searchInput" placeholder="Search projects...">
        </div>
        <select id="categoryFilter" class="filter-select">
            <option value="">All Categories</option>
            <option value="mine_tech">Mining Technology</option>
            <option value="enviro">Environmental</option>
            <option value="startup">Startup</option>
            <option value="other">Other</option>
        </select>
    </div>

    <!-- All Projects Tab Content -->
    <div id="all-projects" class="tab-content active">
        <div class="projects-table-container">
            @if($allProjects->count() > 0)
                <table class="projects-table">
                    <thead>
                        <tr>
                            <th>Project Title</th>
                            <th>Owner</th>
                            <th>Category</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="projectsTableBody">
                        @foreach($allProjects as $project)
                            <tr data-category="{{ $project->category }}" data-title="{{ strtolower($project->title) }}" data-owner="{{ strtolower($project->user->name) }}">
                                <td>
                                    <div class="project-title-cell">
                                        <div class="project-icon-small">
                                            <svg fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="project-name">{{ $project->title }}</div>
                                            @if($project->description)
                                                <div class="project-desc">{{ Str::limit($project->description, 50) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="owner-cell">
                                        <div class="avatar">{{ substr($project->user->name, 0, 1) }}</div>
                                        {{ $project->user->name }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $project->category }}">
                                        {{ ucfirst(str_replace('_', ' ', $project->category)) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="date-cell">{{ $project->created_at->format('M d, Y') }}</div>
                                </td>
                                <td>
                                    <a href="{{ route('projects.show', $project->id) }}" class="btn-view">
                                        <svg fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                        </svg>
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3>No projects yet</h3>
                    <p>No projects have been created in the system.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- My Projects Tab Content -->
    <div id="my-projects" class="tab-content">
        <div class="projects-grid">
            @forelse($myProjects as $project)
                <div class="project-card">
                    <a href="{{ route('projects.show', $project->id) }}" class="project-card-link">
                        <div class="project-card-header">
                            <div class="project-card-icon">
                                <svg fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="badge badge-{{ $project->category }}">
                                {{ ucfirst(str_replace('_', ' ', $project->category)) }}
                            </span>
                        </div>
                        <h3 class="project-card-title">{{ $project->title }}</h3>
                        <p class="project-card-desc">{{ Str::limit($project->description, 80) }}</p>
                        <div class="project-card-footer">
                            <span class="project-card-date">{{ $project->created_at->diffForHumans() }}</span>
                            @if($project->is_draft)
                                <span class="badge-draft">Draft</span>
                            @endif
                        </div>
                    </a>
                </div>
            @empty
                <div class="empty-state-grid">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3>No personal projects</h3>
                    <p>You haven't created any projects yet.</p>
                    <a href="{{ route('projects.create') }}" class="btn-primary">Create Your First Project</a>
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
.mining-tech-dashboard {
    padding: 0;
}

/* Header */
.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.dashboard-title {
    font-size: 1.875rem;
    font-weight: 700;
    color: #1f2937;
    margin: 0 0 0.25rem 0;
}

.dashboard-subtitle {
    color: #6b7280;
    font-size: 0.95rem;
    margin: 0;
}

.btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: #7CB342;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-primary:hover {
    background: #689f38;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(124, 179, 66, 0.3);
}

.btn-primary svg {
    width: 18px;
    height: 18px;
}

/* Statistics Cards */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1.25rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 1.5rem;
    display: flex;
    gap: 1rem;
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.stat-icon svg {
    width: 24px;
    height: 24px;
}

.stat-content {
    flex: 1;
}

.stat-label {
    font-size: 0.875rem;
    color: #6b7280;
    margin-bottom: 0.25rem;
}

.stat-value {
    font-size: 1.875rem;
    font-weight: 700;
    color: #1f2937;
}

.stat-breakdown {
    font-size: 0.75rem;
    color: #6b7280;
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    margin-top: 0.5rem;
}

/* Tabs */
.dashboard-tabs {
    display: flex;
    gap: 0.5rem;
    border-bottom: 2px solid #e5e7eb;
    margin-bottom: 1.5rem;
}

.tab-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    background: none;
    border: none;
    border-bottom: 2px solid transparent;
    color: #6b7280;
    font-weight: 500;
    cursor: pointer;
    margin-bottom: -2px;
    transition: all 0.2s;
}

.tab-btn svg {
    width: 18px;
    height: 18px;
}

.tab-btn:hover {
    color: #7CB342;
}

.tab-btn.active {
    color: #7CB342;
    border-bottom-color: #7CB342;
}

/* Toolbar */
.toolbar {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.search-box {
    flex: 1;
    position: relative;
    display: flex;
    align-items: center;
}

.search-box svg {
    position: absolute;
    left: 1rem;
    width: 18px;
    height: 18px;
    color: #9ca3af;
}

.search-box input {
    width: 100%;
    padding: 0.75rem 1rem 0.75rem 3rem;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 0.9375rem;
    transition: all 0.2s;
}

.search-box input:focus {
    outline: none;
    border-color: #7CB342;
    box-shadow: 0 0 0 3px rgba(124, 179, 66, 0.1);
}

.filter-select {
    padding: 0.75rem 1rem;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 0.9375rem;
    background: white;
    cursor: pointer;
    transition: all 0.2s;
}

.filter-select:focus {
    outline: none;
    border-color: #7CB342;
}

/* Tab Content */
.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

/* Projects Table */
.projects-table-container {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
}

.projects-table {
    width: 100%;
    border-collapse: collapse;
}

.projects-table thead {
    background: #f9fafb;
}

.projects-table th {
    padding: 0.875rem 1rem;
    text-align: left;
    font-size: 0.8125rem;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.projects-table td {
    padding: 1rem;
    border-top: 1px solid #f3f4f6;
}

.projects-table tbody tr {
    transition: background 0.2s;
}

.projects-table tbody tr:hover {
    background: #f9fafb;
}

.project-title-cell {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.project-icon-small {
    width: 36px;
    height: 36px;
    background: #f3f4f6;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6b7280;
    flex-shrink: 0;
}

.project-icon-small svg {
    width: 18px;
    height: 18px;
}

.project-name {
    font-weight: 500;
    color: #1f2937;
}

.project-desc {
    font-size: 0.8125rem;
    color: #6b7280;
    margin-top: 0.125rem;
}

.owner-cell {
    display: flex;
    align-items: center;
    gap: 0.625rem;
}

.avatar {
    width: 32px;
    height: 32px;
    background: #7CB342;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
}

.date-cell {
    color: #6b7280;
    font-size: 0.875rem;
}

.badge {
    display: inline-block;
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 500;
}

.badge-mine_tech {
    background: #e8f5e9;
    color: #2e7d32;
}

.badge-enviro {
    background: #e3f2fd;
    color: #1565c0;
}

.badge-startup {
    background: #f3e5f5;
    color: #7b1fa2;
}

.badge-other {
    background: #f5f5f5;
    color: #616161;
}

.badge-draft {
    background: #fff3e0;
    color: #e65100;
}

.btn-view {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.5rem 0.875rem;
    background: #f9fafb;
    color: #374151;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.2s;
}

.btn-view:hover {
    background: #7CB342;
    color: white;
    border-color: #7CB342;
}

.btn-view svg {
    width: 16px;
    height: 16px;
}

/* Projects Grid (for My Projects tab) */
.projects-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.25rem;
}

.project-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.2s;
}

.project-card:hover {
    border-color: #7CB342;
    box-shadow: 0 4px 12px rgba(124, 179, 66, 0.15);
    transform: translateY(-2px);
}

.project-card-link {
    display: block;
    padding: 1.5rem;
    text-decoration: none;
    color: inherit;
}

.project-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.project-card-icon {
    width: 40px;
    height: 40px;
    background: #f3f4f6;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6b7280;
}

.project-card-icon svg {
    width: 20px;
    height: 20px;
}

.project-card-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0 0 0.5rem 0;
}

.project-card-desc {
    color: #6b7280;
    font-size: 0.875rem;
    line-height: 1.5;
    margin: 0 0 1rem 0;
}

.project-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1rem;
    border-top: 1px solid #f3f4f6;
}

.project-card-date {
    font-size: 0.8125rem;
    color: #9ca3af;
}

/* Empty States */
.empty-state, .empty-state-grid {
    text-align: center;
    padding: 4rem 2rem;
}

.empty-state svg, .empty-state-grid svg {
    width: 64px;
    height: 64px;
    color: #d1d5db;
    margin: 0 auto 1.5rem;
}

.empty-state h3, .empty-state-grid h3 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #374151;
    margin: 0 0 0.5rem 0;
}

.empty-state p, .empty-state-grid p {
    color: #6b7280;
    margin: 0 0 1.5rem 0;
}

.empty-state-grid {
    grid-column: 1 / -1;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const tabId = btn.getAttribute('data-tab');
            
            // Remove active class from all tabs and contents
            tabBtns.forEach(b => b.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));
            
            // Add active class to clicked tab and corresponding content
            btn.classList.add('active');
            document.getElementById(tabId).classList.add('active');
        });
    });
    
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const tableBody = document.getElementById('projectsTableBody');
    
    function filterProjects() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedCategory = categoryFilter.value;
        const rows = tableBody.querySelectorAll('tr');
        
        rows.forEach(row => {
            const title = row.getAttribute('data-title');
            const owner = row.getAttribute('data-owner');
            const category = row.getAttribute('data-category');
            
            const matchesSearch = title.includes(searchTerm) || owner.includes(searchTerm);
            const matchesCategory = !selectedCategory || category === selectedCategory;
            
            if (matchesSearch && matchesCategory) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    
    searchInput.addEventListener('input', filterProjects);
    categoryFilter.addEventListener('change', filterProjects);
});
</script>
@endsection
