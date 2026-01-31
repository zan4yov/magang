@extends('layouts.app')

@section('title', 'Dashboard - IGMS')

@section('content')
<div class="user-dashboard">
    <!-- Dashboard Header -->
    <div class="dashboard-header-section">
        <div class="dashboard-header-left">
            <h1 class="dashboard-page-title">Dashboard</h1>
        </div>
        <div class="dashboard-header-right">
            <button class="dashboard-action-btn">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                </svg>
                Search
            </button>
            <button class="dashboard-action-btn">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z"/>
                </svg>
                Filter
            </button>
        </div>
    </div>

    <!-- Tabs -->
    <div class="dashboard-tabs">
        <a href="{{ route('projects.index', ['tab' => 'recent']) }}" 
           class="dashboard-tab {{ $tab === 'recent' ? 'active' : '' }}">
            Recently Viewed
        </a>
        <a href="{{ route('projects.index', ['tab' => 'personal']) }}" 
           class="dashboard-tab {{ $tab === 'personal' ? 'active' : '' }}">
            Personal Project
        </a>
        <a href="{{ route('projects.index', ['tab' => 'shared']) }}" 
           class="dashboard-tab {{ $tab === 'shared' ? 'active' : '' }}">
            Shared Project
        </a>
    </div>

    <!-- Projects Grid -->
    <div class="projects-grid">
        @forelse($projects as $project)
            <div class="project-card-simple">
                <a href="{{ route('projects.show', $project->id) }}" class="project-link">
                    <div class="project-icon-simple">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="project-name">{{ $project->title }}</div>
                </a>
                
                <!-- Star Button -->
                <form method="POST" action="{{ route('projects.star', $project->id) }}" class="star-form">
                    @csrf
                    <button type="submit" class="star-btn" title="{{ $project->is_starred ? 'Unstar' : 'Star' }}">
                        <svg fill="{{ $project->is_starred ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 20 20" stroke-width="1.5">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </button>
                </form>
            </div>
        @empty
            <!-- Empty State with Create Project Card -->
            <div style="grid-column: 1 / -1; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 3rem 1rem;">

                
                <!-- Create Project Card -->
                <a href="{{ route('projects.create') }}" class="project-card project-card-add">
                    <div class="project-card-content" style="justify-content: center;">
                        <div class="project-icon-add">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5zm3.293 1.293a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 01-1.414-1.414L7.586 10 5.293 7.707a1 1 0 010-1.414zM11 12a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <div class="project-title-add">New Project</div>
                </a>
                <br>
</br>
                <div style="text-align: center; margin-bottom: 2rem;">
                    <div style="font-size: 1.125rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">No projects yet</div>
                    <div style="color: #6b7280; font-size: 0.9375rem;">Create your first project to get started</div>
                </div>

            </div>
        @endforelse

        <!-- Add Project Card (when there are projects) -->
        @if($projects->count() > 0)
            <a href="{{ route('projects.create') }}" class="project-card project-card-add">
                <div class="project-card-content" style="justify-content: center;">
                    <div class="project-icon-add">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5zm3.293 1.293a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 01-1.414-1.414L7.586 10 5.293 7.707a1 1 0 010-1.414zM11 12a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <div class="project-title-add">New Project</div>
            </a>
        @endif
    </div>
</div>

<style>
.user-dashboard {
    padding: 0;
}

/* Dashboard Header */
.dashboard-header-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.dashboard-page-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
}

.dashboard-header-right {
    display: flex;
    gap: 0.75rem;
}

.dashboard-action-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1rem;
    background: #ffffff;
    color: #6b7280;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.dashboard-action-btn svg {
    width: 16px;
    height: 16px;
}

.dashboard-action-btn:hover {
    background: #f9fafb;
    border-color: #7CB342;
    color: #7CB342;
}

/* Tabs */
.dashboard-tabs {
    display: flex;
    gap: 0.5rem;
    border-bottom: 2px solid #e5e7eb;
    margin-bottom: 2rem;
}

.dashboard-tab {
    padding: 0.75rem 1.5rem;
    color: #6b7280;
    text-decoration: none;
    font-weight: 500;
    border-bottom: 2px solid transparent;
    margin-bottom: -2px;
    transition: all 0.2s;
}

.dashboard-tab:hover {
    color: #7CB342;
}

.dashboard-tab.active {
    color: #7CB342;
    border-bottom-color: #7CB342;
}

/* Projects Grid */
.projects-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 1rem;
    margin-top: 1.5rem;
}

/* Simplified Project Cards */
.project-card-simple {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 1.25rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap:1rem;
   transition: all 0.2s;
}

.project-card-simple:hover {
    border-color: #7CB342;
    box-shadow: 0 4px 12px rgba(124, 179, 66, 0.15);
}

.project-link {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex: 1;
    text-decoration: none;
    min-width: 0;
}

.project-icon-simple {
    width: 40px;
    height: 40px;
    background: #f3f4f6;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6b7280;
    flex-shrink: 0;
}

.project-icon-simple svg {
    width: 20px;
    height: 20px;
}

.project-name {
    font-size: 0.9375rem;
    font-weight: 500;
    color: #1f2937;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.star-form {
    flex-shrink: 0;
}

.star-btn {
    width: 36px;
    height: 36px;
    background: #fff7ed;
    color: #f59e0b;
    border: none;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
}

.star-btn:hover {
    background: #fef3c7;
    transform: scale(1.05);
}

.star-btn svg {
    width: 18px;
    height: 18px;
}


/* Star Button */
.project-star-form {
    position: absolute;
    top: -1rem;
    right: 0.5rem;
}

.project-star-btn {
    width: 28px;
    height: 28px;
    padding: 0;
    background: rgba(255, 255, 255, 0.9);
    border: none;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    color: #f59e0b;
}

.project-star-btn svg {
    width: 16px;
    height: 16px;
}

.project-star-btn:hover {
    background: #ffffff;
    transform: scale(1.1);
}

.project-star-btn.starred {
    color: #f59e0b;
}

/* Project Title */
.project-title {
    margin-top: 0.75rem;
    font-weight: 600;
    color: #ffffff;
    text-align: center;
    font-size: 0.9rem;
}

/* Add Project Card */
.project-card-add {
    background: #ffffff;
    border: 2px dashed #d1d5db;
    cursor: pointer;
    text-decoration: none;
    max-width: 180px;
}

.project-card-add:hover {
    border-color: #7CB342;
    background: #f9fafb;
}

.project-icon-add {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #9ca3af;
}

.project-card-add:hover .project-icon-add {
    background: rgba(124, 179, 66, 0.1);
    color: #7CB342;
}

.project-icon-add svg {
    width: 32px;
    height: 32px;
}

.project-title-add {
    margin-top: 0.75rem;
    font-weight: 600;
    color: #9ca3af;
    text-align: center;
    font-size: 0.9rem;
}

.project-card-add:hover .project-title-add {
    color: #7CB342;
}


/* Empty State */
.empty-state-wrapper {
    grid-column: 1 / -1;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
}

.empty-state-icon {
    width: 64px;
    height: 64px;
    color: #d1d5db;
    margin: 0 auto 1.5rem;
}

.empty-state-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
}

.empty-state-text {
    color: #6b7280;
    font-size: 0.95rem;
}
</style>
@endsection
