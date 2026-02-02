@extends('layouts.app')

@section('title', 'Starred - IGMS')

@section('content')
<div class="user-dashboard">
    <!-- Page Header -->
    <div style="margin-bottom: 2.5rem;">
        <h1 style="font-size: 1.5rem; font-weight: 600; color: #1f2937; margin: 0 0 0.5rem 0;">Starred Projects</h1>
        <p style="margin: 0; color: #6b7280; font-size: 0.95rem;">Your favorite projects for quick access</p>
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
                    <button type="submit" class="star-btn starred" title="Remove from starred">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </button>
                </form>
            </div>
        @empty
            <div class="empty-state-wrapper">
                <div class="empty-state">
                    <div class="empty-state-title">No starred projects</div>
                    <div class="empty-state-text">Star your favorite projects for quick access</div>
                </div>
            </div>
        @endforelse
    </div>
</div>

<style>
.projects-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 1rem;
}

.project-card-simple {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 1.25rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
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
    background: #fef3c7;
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
    background: #f59e0b;
    color: #ffffff;
    transform: scale(1.05);
}

.star-btn svg {
    width: 18px;
    height: 18px;
}
</style>
@endsection
