@extends('layouts.app')

@section('title', 'Starred - IGMS')

@section('content')
<div class="user-dashboard">
    <!-- Dashboard Header -->
    <div class="dashboard-header-section">
        <div class="dashboard-header-left">
            <h1 class="dashboard-page-title">Starred Projects</h1>
            <p class="dashboard-subtitle" style="margin-top: 0.5rem; color: #6b7280;">Your favorite projects for quick access</p>
        </div>
    </div>

    <!-- Projects Grid -->
    <div class="projects-grid">
        @forelse($projects as $project)
            <div class="project-card">
                <div class="project-accent project-accent-{{ $project->color_accent }}"></div>
                <div class="project-card-content">
                    <a href="{{ route('projects.show', $project->id) }}" class="project-card-link">
                        <div class="project-icon">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                    </a>
                    <form method="POST" action="{{ route('projects.star', $project->id) }}" class="project-star-form">
                        @csrf
                        <button type="submit" class="project-star-btn starred">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </button>
                    </form>
                </div>
                <div class="project-title">{{ $project->title }}</div>
            </div>
        @empty
            <div class="empty-state-wrapper">
                <div class="empty-state">
                    <svg class="empty-state-icon" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <div class="empty-state-title">No starred projects</div>
                    <div class="empty-state-text">Star your favorite projects for quick access</div>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
