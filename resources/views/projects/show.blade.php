@extends('layouts.app')

@section('title', $project->title . ' - IGMS')

@section('content')
<div class="dashboard-container">
    <!-- Header -->
    <div style="margin-bottom: 2rem;">
        <a href="{{ route('projects.index') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; color: #6b7280; text-decoration: none; margin-bottom: 1rem; font-size: 0.95rem;">
            <svg style="width: 18px; height: 18px;" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
            </svg>
            <span>Back to Dashboard</span>
        </a>
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <h1 class="dashboard-title">{{ $project->title }}</h1>
                <p class="dashboard-subtitle">
                    <span class="badge badge-{{ $project->color_accent }}">{{ ucfirst(str_replace('_', ' ', $project->category)) }}</span>
                    @if($project->is_draft)
                        <span class="badge" style="background: #f59e0b; color: #ffffff; margin-left: 0.5rem;">Draft</span>
                    @endif
                </p>
            </div>
            <div style="display: flex; gap: 0.75rem;">
                @if($project->user_id === Auth::id())
                    <a href="{{ route('projects.edit', $project->id) }}" class="btn" style="background: #7CB342; color: #ffffff;">
                        Edit Project
                    </a>
                    <form method="POST" action="{{ route('projects.destroy', $project->id) }}" style="display: inline;" onsubmit="return confirm('Move this project to trash?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn" style="background: #ef4444; color: #ffffff;">Delete</button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Project Content -->
    <div style="background: #ffffff; border-radius: 16px; padding: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="border-left: 4px solid {{ 
            $project->color_accent === 'red' ? '#ef4444' :
            ($project->color_accent === 'blue' ? '#3b82f6' :
            ($project->color_accent === 'green' ? '#10b981' :
            ($project->color_accent === 'yellow' ? '#f59e0b' : '#8b5cf6')))
        }}; padding-left: 1.5rem; margin-bottom: 2rem;">
            <div style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem;">Description</div>
            <div style="color: #1f2937; font-size: 1rem; line-height: 1.6;">
                {{ $project->description ?? 'No description provided' }}
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb;">
            <div>
                <div style="color: #6b7280; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Owner</div>
                <div style="color: #1f2937; font-weight: 500;">{{ $project->user->name }}</div>
            </div>
            <div>
                <div style="color: #6b7280; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Created</div>
                <div style="color: #1f2937; font-weight: 500;">{{ $project->created_at->format('d M Y') }}</div>
            </div>
            <div>
                <div style="color: #6b7280; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Last Viewed</div>
                <div style="color: #1f2937; font-weight: 500;">{{ $project->last_viewed_at ? $project->last_viewed_at->diffForHumans() : 'Never' }}</div>
            </div>
        </div>
    </div>
</div>

<style>
.badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.875rem;
    font-weight: 500;
}

.badge-red { background: #fee2e2; color: #991b1b; }
.badge-blue { background: #dbeafe; color: #1e40af; }
.badge-green { background: #d1fae5; color: #065f46; }
.badge-yellow { background: #fef3c7; color: #92400e; }
.badge-purple { background: #ede9fe; color: #5b21b6; }
</style>
@endsection
