@extends('layouts.app')

@section('title', 'View Project - Super Admin')

@section('content')
<div class="dashboard-container">
    <!-- Header -->
    <div style="margin-bottom: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
            <div>
                <a href="{{ route('admin.projects.index') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; color: #6b7280; text-decoration: none; font-size: 0.95rem; margin-bottom: 0.75rem;">
                    <svg style="width: 16px; height: 16px;" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                    </svg>
                    Back to Projects
                </a>
                <h1 class="dashboard-title" style="margin: 0;">{{ $project->title }}</h1>
                <p class="dashboard-subtitle" style="margin-top: 0.5rem;">Project Details (Read-Only View)</p>
            </div>
            <div style="display: flex; gap: 0.5rem; align-items: center;">
                @if($project->deleted_at)
                    <span class="role-badge" style="background: #fee2e2; color: #dc2626; font-size: 1rem; padding: 0.5rem 1rem;">Trashed</span>
                @elseif($project->is_draft)
                    <span class="role-badge" style="background: #fff3e0; color: #f57c00; font-size: 1rem; padding: 0.5rem 1rem;">Draft</span>
                @else
                    <span class="role-badge role-mining-tech" style="font-size: 1rem; padding: 0.5rem 1rem;">Completed</span>
                @endif
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
        <!-- Main Content -->
        <div>
            <!-- Project Information -->
            <div style="background: #ffffff; border-radius: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 2rem; margin-bottom: 2rem;">
                <h2 style="font-size: 1.25rem; font-weight: 600; color: #1f2937; margin: 0 0 1.5rem 0;">Project Information</h2>
                
                <div style="display: grid; gap: 1.5rem;">
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #6b7280; margin-bottom: 0.5rem;">Description</label>
                        <p style="color: #1f2937; margin: 0; line-height: 1.6;">{{ $project->description ?? 'No description provided' }}</p>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
                        <div>
                            <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #6b7280; margin-bottom: 0.5rem;">Category</label>
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
                            <span style="display: inline-block; padding: 0.375rem 1rem; border-radius: 12px; font-size: 0.95rem; font-weight: 500; background: {{ $categoryColors[$project->category] }}20; color: {{ $categoryColors[$project->category] }};">
                                {{ $categoryLabels[$project->category] }}
                            </span>
                        </div>

                        <div>
                            <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #6b7280; margin-bottom: 0.5rem;">Color Accent</label>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                @php
                                    $colors = [
                                        'red' => '#EF4444',
                                        'blue' => '#3B82F6',
                                        'green' => '#10B981',
                                        'yellow' => '#F59E0B',
                                        'purple' => '#8B5CF6'
                                    ];
                                @endphp
                                <div style="width: 24px; height: 24px; border-radius: 6px; background: {{ $colors[$project->color_accent] }}; border: 2px solid #e5e7eb;"></div>
                                <span style="text-transform: capitalize; color: #1f2937;">{{ $project->color_accent }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empathy Map -->
            @if($project->hasEmpathyMap())
            <div style="background: #ffffff; border-radius: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 2rem; margin-bottom: 2rem;">
                <h2 style="font-size: 1.25rem; font-weight: 600; color: #1f2937; margin: 0 0 1.5rem 0;">Empathy Map</h2>
                
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
                    <div>
                        <h3 style="font-size: 1rem; font-weight: 600; color: #2196F3; margin: 0 0 0.75rem 0;">Says</h3>
                        <ul style="margin: 0; padding-left: 1.25rem; color: #4b5563;">
                            @foreach($project->empathy_says as $item)
                                <li style="margin-bottom: 0.5rem;">{{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div>
                        <h3 style="font-size: 1rem; font-weight: 600; color: #7CB342; margin: 0 0 0.75rem 0;">Thinks</h3>
                        <ul style="margin: 0; padding-left: 1.25rem; color: #4b5563;">
                            @foreach($project->empathy_thinks as $item)
                                <li style="margin-bottom: 0.5rem;">{{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div>
                        <h3 style="font-size: 1rem; font-weight: 600; color: #FF9800; margin: 0 0 0.75rem 0;">Does</h3>
                        <ul style="margin: 0; padding-left: 1.25rem; color: #4b5563;">
                            @foreach($project->empathy_does as $item)
                                <li style="margin-bottom: 0.5rem;">{{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div>
                        <h3 style="font-size: 1rem; font-weight: 600; color: #9C27B0; margin: 0 0 0.75rem 0;">Feels</h3>
                        <ul style="margin: 0; padding-left: 1.25rem; color: #4b5563;">
                            @foreach($project->empathy_feels as $item)
                                <li style="margin-bottom: 0.5rem;">{{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <!-- Customer Profile -->
            @if($project->hasCustomerProfile())
            <div style="background: #ffffff; border-radius: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 2rem; margin-bottom: 2rem;">
                <h2 style="font-size: 1.25rem; font-weight: 600; color: #1f2937; margin: 0 0 1.5rem 0;">Customer Profile (AI Generated)</h2>
                
                <div style="display: grid; gap: 1.5rem;">
                    <div>
                        <h3 style="font-size: 1rem; font-weight: 600; color: #2196F3; margin: 0 0 0.75rem 0;">Customer Jobs</h3>
                        <ul style="margin: 0; padding-left: 1.25rem; color: #4b5563;">
                            @foreach($project->customer_jobs as $item)
                                <li style="margin-bottom: 0.5rem;">{{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div>
                        <h3 style="font-size: 1rem; font-weight: 600; color: #EF4444; margin: 0 0 0.75rem 0;">Customer Pains</h3>
                        <ul style="margin: 0; padding-left: 1.25rem; color: #4b5563;">
                            @foreach($project->customer_pains as $item)
                                <li style="margin-bottom: 0.5rem;">{{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div>
                        <h3 style="font-size: 1rem; font-weight: 600; color: #10B981; margin: 0 0 0.75rem 0;">Customer Gains</h3>
                        <ul style="margin: 0; padding-left: 1.25rem; color: #4b5563;">
                            @foreach($project->customer_gains as $item)
                                <li style="margin-bottom: 0.5rem;">{{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <!-- Shared Users -->
            @if($project->sharedWith->count() > 0)
            <div style="background: #ffffff; border-radius: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 2rem;">
                <h2 style="font-size: 1.25rem; font-weight: 600; color: #1f2937; margin: 0 0 1.5rem 0;">Shared With</h2>
                
                <div style="display: grid; gap: 1rem;">
                    @foreach($project->sharedWith as $sharedUser)
                    <div style="display: flex; align-items: center; gap: 1rem; padding: 0.75rem; background: #f9fafb; border-radius: 8px;">
                        <div class="user-avatar" style="width: 40px; height: 40px;">
                            {{ substr($sharedUser->name, 0, 1) }}
                        </div>
                        <div style="flex: 1;">
                            <div style="font-weight: 500; color: #1f2937;">{{ $sharedUser->name }}</div>
                            <div style="font-size: 0.875rem; color: #6b7280;">{{ $sharedUser->email }}</div>
                        </div>
                        <div>
                            @if($sharedUser->pivot->can_edit)
                                <span class="role-badge role-mining-tech">Can Edit</span>
                            @else
                                <span class="role-badge role-user">View Only</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div>
            <!-- Owner Information -->
            <div style="background: #ffffff; border-radius: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 1.5rem; margin-bottom: 2rem;">
                <h3 style="font-size: 1rem; font-weight: 600; color: #6b7280; margin: 0 0 1rem 0; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.875rem;">Project Owner</h3>
                
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                    <div class="user-avatar" style="width: 48px; height: 48px; font-size: 1.25rem;">
                        {{ substr($project->user->name, 0, 1) }}
                    </div>
                    <div>
                        <div style="font-weight: 600; color: #1f2937; font-size: 1rem;">{{ $project->user->name }}</div>
                        <div style="font-size: 0.875rem; color: #6b7280;">{{ $project->user->email }}</div>
                    </div>
                </div>

                <div style="padding-top: 1rem; border-top: 1px solid #e5e7eb;">
                    <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">Role</div>
                    @if($project->user->role === 'super_admin')
                        <span class="role-badge role-super-admin">Super Admin</span>
                    @elseif($project->user->role === 'mining_tech')
                        <span class="role-badge role-mining-tech">Mining Tech</span>
                    @else
                        <span class="role-badge role-user">User</span>
                    @endif
                </div>
            </div>

            <!-- Project Metadata -->
            <div style="background: #ffffff; border-radius: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 1.5rem;">
                <h3 style="font-size: 1rem; font-weight: 600; color: #6b7280; margin: 0 0 1rem 0; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.875rem;">Project Metadata</h3>
                
                <div style="display: grid; gap: 1rem;">
                    <div>
                        <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">Created</div>
                        <div style="font-weight: 500; color: #1f2937;">{{ $project->created_at->format('d M Y, H:i') }}</div>
                        <div style="font-size: 0.875rem; color: #6b7280;">{{ $project->created_at->diffForHumans() }}</div>
                    </div>

                    <div>
                        <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">Last Updated</div>
                        <div style="font-weight: 500; color: #1f2937;">{{ $project->updated_at->format('d M Y, H:i') }}</div>
                        <div style="font-size: 0.875rem; color: #6b7280;">{{ $project->updated_at->diffForHumans() }}</div>
                    </div>

                    @if($project->last_viewed_at)
                    <div>
                        <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">Last Viewed</div>
                        <div style="font-weight: 500; color: #1f2937;">{{ $project->last_viewed_at->format('d M Y, H:i') }}</div>
                        <div style="font-size: 0.875rem; color: #6b7280;">{{ $project->last_viewed_at->diffForHumans() }}</div>
                    </div>
                    @endif

                    @if($project->deleted_at)
                    <div>
                        <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">Deleted</div>
                        <div style="font-weight: 500; color: #dc2626;">{{ $project->deleted_at->format('d M Y, H:i') }}</div>
                        <div style="font-size: 0.875rem; color: #6b7280;">{{ $project->deleted_at->diffForHumans() }}</div>
                    </div>
                    @endif

                    <div>
                        <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">Starred</div>
                        <div style="font-weight: 500; color: #1f2937;">{{ $project->is_starred ? 'Yes' : 'No' }}</div>
                    </div>

                    <div>
                        <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">Empathy Map</div>
                        <div style="font-weight: 500; color: #1f2937;">{{ $project->empathy_map_completed ? 'Completed' : 'Not completed' }}</div>
                    </div>

                    <div>
                        <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">Customer Profile</div>
                        <div style="font-weight: 500; color: #1f2937;">{{ $project->customer_profile_generated ? 'Generated' : 'Not generated' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.user-avatar {
    border-radius: 50%;
    background: linear-gradient(135deg, #7CB342 0%, #558B2F 100%);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
}
</style>
@endsection
