@extends('layouts.app')

@section('title', 'Manage Projects - Super Admin')

@section('content')
<div class="dashboard-container">
    <!-- Header -->
    <div class="dashboard-welcome" style="margin-bottom: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 class="dashboard-title">Project Management</h1>
                <p class="dashboard-subtitle">View and manage all projects in the system</p>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">Total Projects</div>
                <div style="font-size: 2rem; font-weight: 700; color: #2196F3;">{{ number_format($totalProjects) }}</div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div style="background: #ffffff; border-radius: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 1.5rem; margin-bottom: 2rem;">
        <form method="GET" action="{{ route('admin.projects.index') }}" id="filterForm">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <!-- Search -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Project title or owner..." style="width: 100%; padding: 0.625rem 1rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem;">
                </div>

                <!-- Status Filter -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Status</label>
                    <select name="status" style="width: 100%; padding: 0.625rem 1rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem;">
                        <option value="">All Status</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="trashed" {{ request('status') == 'trashed' ? 'selected' : '' }}>Trashed</option>
                    </select>
                </div>

                <!-- Category Filter -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Category</label>
                    <select name="category" style="width: 100%; padding: 0.625rem 1rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem;">
                        <option value="">All Categories</option>
                        <option value="mine_tech" {{ request('category') == 'mine_tech' ? 'selected' : '' }}>Mine Tech</option>
                        <option value="enviro" {{ request('category') == 'enviro' ? 'selected' : '' }}>Enviro</option>
                        <option value="startup" {{ request('category') == 'startup' ? 'selected' : '' }}>Startup</option>
                        <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <!-- Date From -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Date From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" style="width: 100%; padding: 0.625rem 1rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem;">
                </div>

                <!-- Date To -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">Date To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" style="width: 100%; padding: 0.625rem 1rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 0.95rem;">
                </div>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 1rem; justify-content: flex-end;">
                <button type="submit" class="btn btn-primary">Apply Filters</button>
                <a href="{{ route('admin.projects.index') }}" class="btn" style="background: #f3f4f6; color: #374151;">Clear Filters</a>
            </div>
        </form>
    </div>

    <!-- Stats Summary -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
        <div style="background: #e3f2fd; padding: 1rem; border-radius: 12px;">
            <div style="font-size: 0.875rem; color: #1976D2; font-weight: 500;">Total Projects</div>
            <div style="font-size: 1.75rem; font-weight: 700; color: #2196F3;">{{ number_format($totalProjects) }}</div>
        </div>
        <div style="background: #e8f5e9; padding: 1rem; border-radius: 12px;">
            <div style="font-size: 0.875rem; color: #558B2F; font-weight: 500;">Completed</div>
            <div style="font-size: 1.75rem; font-weight: 700; color: #7CB342;">{{ number_format($completedProjects) }}</div>
        </div>
        <div style="background: #fff3e0; padding: 1rem; border-radius: 12px;">
            <div style="font-size: 0.875rem; color: #F57C00; font-weight: 500;">Drafts</div>
            <div style="font-size: 1.75rem; font-weight: 700; color: #FF9800;">{{ number_format($draftProjects) }}</div>
        </div>
    </div>

    <!-- Projects Table -->
    <div style="background: #ffffff; border-radius: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
        <div class="activity-table-container">
            <table class="activity-table">
                <thead>
                    <tr>
                        <th style="width: 30%;">Project Title</th>
                        <th>Owner</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Last Updated</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($projects as $project)
                    <tr>
                        <td>
                            <div style="font-weight: 500; color: #1f2937;">{{ $project->title }}</div>
                            @if($project->description)
                                <div style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;">
                                    {{ Str::limit($project->description, 60) }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <div class="user-avatar" style="width: 32px; height: 32px; font-size: 0.875rem;">
                                    {{ substr($project->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div style="font-weight: 500;">{{ $project->user->name }}</div>
                                    <div style="font-size: 0.75rem; color: #6b7280;">{{ $project->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @php
                                $categoryColors = [
                                    'mine_tech' => '#2196F3',
                                    'enviro' => '#4CAF50',
                                    'startup' => '#FF9800',
                                    'other' => '#9E9E9E'
                                ];
                                $categoryLabels = [
                                    'mine_tech' => 'Mine Tech',
                                    'enviro' => 'Enviro',
                                    'startup' => 'Startup',
                                    'other' => 'Other'
                                ];
                            @endphp
                            <span style="display: inline-block; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.875rem; font-weight: 500; background: {{ $categoryColors[$project->category] }}20; color: {{ $categoryColors[$project->category] }};">
                                {{ $categoryLabels[$project->category] }}
                            </span>
                        </td>
                        <td>
                            @if($project->deleted_at)
                                <span class="role-badge" style="background: #fee2e2; color: #dc2626;">Trashed</span>
                            @elseif($project->is_draft)
                                <span class="role-badge" style="background: #fff3e0; color: #f57c00;">Draft</span>
                            @else
                                <span class="role-badge role-mining-tech">Completed</span>
                            @endif
                        </td>
                        <td>
                            <div>{{ $project->created_at->format('d M Y') }}</div>
                            <div style="font-size: 0.75rem; color: #6b7280;">{{ $project->created_at->format('H:i') }}</div>
                        </td>
                        <td>
                            <div>{{ $project->updated_at->format('d M Y') }}</div>
                            <div style="font-size: 0.75rem; color: #6b7280;">{{ $project->updated_at->diffForHumans() }}</div>
                        </td>
                        <td style="text-align: center;">
                            <a href="{{ route('admin.projects.show', $project->id) }}" class="action-btn action-btn-edit" title="View Project">
                                <svg fill="currentColor" viewBox="0 0 20 20" style="width: 16px; height: 16px;">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 3rem; color: #6b7280;">
                            <svg style="width: 48px; height: 48px; margin: 0 auto 1rem; opacity: 0.5;" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                            </svg>
                            <div style="font-size: 1.125rem; font-weight: 500; margin-bottom: 0.5rem;">No projects found</div>
                            <div style="font-size: 0.95rem;">Try adjusting your filters or search criteria</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($projects->hasPages())
        <div style="padding: 1.5rem; border-top: 1px solid #e5e7eb;">
            {{ $projects->links() }}
        </div>
        @endif
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
