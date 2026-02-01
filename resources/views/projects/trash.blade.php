@extends('layouts.app')

@section('title', 'Trash - IGMS')

@section('content')
<div class="user-dashboard">
    <!-- Page Header -->
    <div style="margin-bottom: 2.5rem;">
        <h1 style="font-size: 1.5rem; font-weight: 600; color: #1f2937; margin: 0 0 0.5rem 0;">Trash</h1>
        <p style="margin: 0 0 0.75rem 0; color: #6b7280; font-size: 0.95rem;">Deleted projects (auto-delete after 30 days)</p>
        
        <div style="display: flex; gap: 1rem; align-items: center;">
            <button onclick="if(confirm('Restore all items from trash?')) { document.getElementById('restore-all-form').submit(); }" 
                    style="background: none; border: none; padding: 0; color: #1f2937; text-decoration: underline; font-size: 0.875rem; cursor: pointer; font-weight: 500;">
                Restore All Items
            </button>
            <button onclick="if(confirm('Permanently delete all items? This cannot be undone!')) { document.getElementById('empty-trash-form').submit(); }"
                    style="background: #ef4444; color: #ffffff; border: none; padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.875rem; font-weight: 500; cursor: pointer;">
                Empty trash
            </button>
        </div>
    </div>

    <!-- Hidden forms for bulk actions -->
    <form id="restore-all-form" method="POST" action="{{ route('projects.restore-all') }}" style="display: none;">
        @csrf
    </form>
    <form id="empty-trash-form" method="POST" action="{{ route('projects.empty-trash') }}" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <!-- Projects Grid -->
    <div class="projects-grid">
        @forelse($projects as $project)
            <div class="project-card-simple">
                <div class="project-link">
                    <div class="project-icon-simple">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="project-name">{{ $project->title }}</div>
                </div>
                
                <div style="display: flex; gap: 0.5rem;">
                    <!-- Restore Button -->
                    <form method="POST" action="{{ route('projects.restore', $project->id) }}" class="restore-form">
                        @csrf
                        <button type="submit" class="restore-btn" title="Restore project">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </form>
                    
                    <!-- Permanent Delete Button -->
                    <form method="POST" action="{{ route('projects.force-delete', $project->id) }}" class="delete-form" onsubmit="return confirm('Are you sure you want to permanently delete this project? This action cannot be undone!')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-btn" title="Delete permanently">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="empty-state-wrapper">
                <div class="empty-state">
                    <div class="empty-state-title">Trash is empty</div>
                    <div class="empty-state-text">No deleted projects</div>
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

.restore-form,
.delete-form {
    flex-shrink: 0;
}

.restore-btn {
    width: 36px;
    height: 36px;
    background: #d1fae5;
    color: #10b981;
    border: none;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
}

.restore-btn:hover {
    background: #10b981;
    color: #ffffff;
    transform: scale(1.05);
}

.restore-btn svg {
    width: 18px;
    height: 18px;
}

.delete-btn {
    width: 36px;
    height: 36px;
    background: #fee2e2;
    color: #ef4444;
    border: none;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
}

.delete-btn:hover {
    background: #ef4444;
    color: #ffffff;
    transform: scale(1.05);
}

.delete-btn svg {
    width: 18px;
    height: 18px;
}
</style>
@endsection
