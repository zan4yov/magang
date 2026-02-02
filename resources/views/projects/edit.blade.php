@extends('layouts.app')

@section('title', 'Edit ' . $project->title . ' - IGMS')

@section('content')
<div class="dashboard-container">
    <!-- Header -->
    <div style="margin-bottom: 2rem;">
        <a href="{{ route('projects.show', $project->id) }}" style="display: inline-flex; align-items: center; gap: 0.5rem; color: #6b7280; text-decoration: none; margin-bottom: 1rem; font-size: 0.95rem;">
            <svg style="width: 18px; height: 18px;" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
            </svg>
            <span>Back to Project</span>
        </a>
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <h1 class="dashboard-title">Edit Project</h1>
                <p class="dashboard-subtitle">{{ $project->title }}</p>
            </div>
            <div style="display: flex; gap: 0.75rem;">
                @if($project->is_draft)
                    <span class="badge" style="background: #f59e0b; color: #ffffff; padding: 0.5rem 1rem; border-radius: 8px;">Draft</span>
                @else
                    <span class="badge" style="background: #10b981; color: #ffffff; padding: 0.5rem 1rem; border-radius: 8px;">Completed</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div style="display: flex; gap: 0.5rem; margin-bottom: 2rem; border-bottom: 2px solid #e5e7eb; padding-bottom: 0;">
        <button class="tab-btn active" data-tab="info" onclick="switchTab('info')">
            📋 Project Info
        </button>
        <button class="tab-btn" data-tab="empathy" onclick="switchTab('empathy')">
            🧠 Empathy Map
        </button>
        <button class="tab-btn" data-tab="profile" onclick="switchTab('profile')">
            👤 Customer Profile
        </button>
    </div>

    <!-- Tab Content: Project Info -->
    <div id="tab-info" class="tab-content active">
        <div style="background: #ffffff; border-radius: 16px; padding: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <form method="POST" action="{{ route('projects.update', $project->id) }}">
                @csrf
                @method('PUT')
                
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label">Project Title</label>
                    <input type="text" name="title" value="{{ old('title', $project->title) }}" class="form-input" required>
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-input" rows="4">{{ old('description', $project->description) }}</textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-input" required>
                            <option value="mine_tech" {{ $project->category === 'mine_tech' ? 'selected' : '' }}>Mining Technology</option>
                            <option value="enviro" {{ $project->category === 'enviro' ? 'selected' : '' }}>Environment</option>
                            <option value="startup" {{ $project->category === 'startup' ? 'selected' : '' }}>Startup</option>
                            <option value="other" {{ $project->category === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Color Accent</label>
                        <select name="color_accent" class="form-input" required>
                            <option value="red" {{ $project->color_accent === 'red' ? 'selected' : '' }}>🔴 Red</option>
                            <option value="blue" {{ $project->color_accent === 'blue' ? 'selected' : '' }}>🔵 Blue</option>
                            <option value="green" {{ $project->color_accent === 'green' ? 'selected' : '' }}>🟢 Green</option>
                            <option value="yellow" {{ $project->color_accent === 'yellow' ? 'selected' : '' }}>🟡 Yellow</option>
                            <option value="purple" {{ $project->color_accent === 'purple' ? 'selected' : '' }}>🟣 Purple</option>
                        </select>
                    </div>
                </div>

                <div style="display: flex; gap: 1rem; justify-content: flex-end; padding-top: 1.5rem; border-top: 1px solid #e5e7eb;">
                    <a href="{{ route('projects.show', $project->id) }}" class="btn" style="background: #e5e7eb; color: #374151;">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tab Content: Empathy Map -->
    <div id="tab-empathy" class="tab-content" style="display: none;">
        @if($project->empathy_map_completed)
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <!-- Says -->
            <div class="empathy-card" style="background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div style="background: #3b82f6; color: #ffffff; padding: 1rem 1.5rem;">
                    <h3 style="margin: 0; font-size: 1.125rem;">💬 Says</h3>
                    <p style="margin: 0.25rem 0 0 0; font-size: 0.875rem; opacity: 0.9;">What the user says out loud</p>
                </div>
                <div style="padding: 1.5rem;">
                    <ul style="margin: 0; padding-left: 1.25rem; color: #374151; line-height: 1.8;">
                        @foreach($project->empathy_says ?? [] as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Thinks -->
            <div class="empathy-card" style="background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div style="background: #8b5cf6; color: #ffffff; padding: 1rem 1.5rem;">
                    <h3 style="margin: 0; font-size: 1.125rem;">💭 Thinks</h3>
                    <p style="margin: 0.25rem 0 0 0; font-size: 0.875rem; opacity: 0.9;">What the user thinks privately</p>
                </div>
                <div style="padding: 1.5rem;">
                    <ul style="margin: 0; padding-left: 1.25rem; color: #374151; line-height: 1.8;">
                        @foreach($project->empathy_thinks ?? [] as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Does -->
            <div class="empathy-card" style="background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div style="background: #10b981; color: #ffffff; padding: 1rem 1.5rem;">
                    <h3 style="margin: 0; font-size: 1.125rem;">🎯 Does</h3>
                    <p style="margin: 0.25rem 0 0 0; font-size: 0.875rem; opacity: 0.9;">Actions and behaviors</p>
                </div>
                <div style="padding: 1.5rem;">
                    <ul style="margin: 0; padding-left: 1.25rem; color: #374151; line-height: 1.8;">
                        @foreach($project->empathy_does ?? [] as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Feels -->
            <div class="empathy-card" style="background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div style="background: #ef4444; color: #ffffff; padding: 1rem 1.5rem;">
                    <h3 style="margin: 0; font-size: 1.125rem;">❤️ Feels</h3>
                    <p style="margin: 0.25rem 0 0 0; font-size: 0.875rem; opacity: 0.9;">Emotions and feelings</p>
                </div>
                <div style="padding: 1.5rem;">
                    <ul style="margin: 0; padding-left: 1.25rem; color: #374151; line-height: 1.8;">
                        @foreach($project->empathy_feels ?? [] as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div style="margin-top: 1.5rem; text-align: center;">
            <a href="{{ route('projects.empathy-map', $project->id) }}" class="btn" style="background: #7CB342; color: #ffffff;">
                ✏️ Edit Empathy Map
            </a>
        </div>
        @else
        <div style="background: #ffffff; border-radius: 16px; padding: 3rem; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <svg style="width: 64px; height: 64px; margin: 0 auto 1rem; color: #d1d5db;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
            </svg>
            <h3 style="color: #1f2937; font-size: 1.25rem; margin-bottom: 0.5rem;">No Empathy Map Yet</h3>
            <p style="color: #6b7280; margin-bottom: 1.5rem;">Complete the empathy map to generate customer insights.</p>
            <a href="{{ route('projects.empathy-map', $project->id) }}" class="btn btn-primary">
                Create Empathy Map
            </a>
        </div>
        @endif
    </div>

    <!-- Tab Content: Customer Profile -->
    <div id="tab-profile" class="tab-content" style="display: none;">
        @if($project->customer_profile_generated)
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem;">
            <!-- Customer Jobs -->
            <div class="profile-card" style="background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div style="background: #3b82f6; color: #ffffff; padding: 1.25rem 1.5rem;">
                    <h3 style="margin: 0; font-size: 1.125rem; font-weight: 600;">⚙️ Customer Jobs</h3>
                    <p style="margin: 0.25rem 0 0 0; font-size: 0.875rem; opacity: 0.9;">What they're trying to accomplish</p>
                </div>
                <div style="padding: 1.5rem;">
                    @forelse($project->customer_jobs ?? [] as $job)
                        <div style="background: #f0f9ff; border-left: 3px solid #3b82f6; padding: 0.875rem 1rem; margin-bottom: 0.75rem; border-radius: 0 8px 8px 0; font-size: 0.95rem; color: #1e40af; line-height: 1.5;">
                            {{ $job }}
                        </div>
                    @empty
                        <p style="color: #9ca3af; text-align: center;">No jobs defined</p>
                    @endforelse
                </div>
            </div>

            <!-- Pains -->
            <div class="profile-card" style="background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div style="background: #ef4444; color: #ffffff; padding: 1.25rem 1.5rem;">
                    <h3 style="margin: 0; font-size: 1.125rem; font-weight: 600;">😰 Pains</h3>
                    <p style="margin: 0.25rem 0 0 0; font-size: 0.875rem; opacity: 0.9;">Frustrations and obstacles</p>
                </div>
                <div style="padding: 1.5rem;">
                    @forelse($project->customer_pains ?? [] as $pain)
                        <div style="background: #fef2f2; border-left: 3px solid #ef4444; padding: 0.875rem 1rem; margin-bottom: 0.75rem; border-radius: 0 8px 8px 0; font-size: 0.95rem; color: #991b1b; line-height: 1.5;">
                            {{ $pain }}
                        </div>
                    @empty
                        <p style="color: #9ca3af; text-align: center;">No pains defined</p>
                    @endforelse
                </div>
            </div>

            <!-- Gains -->
            <div class="profile-card" style="background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div style="background: #10b981; color: #ffffff; padding: 1.25rem 1.5rem;">
                    <h3 style="margin: 0; font-size: 1.125rem; font-weight: 600;">😊 Gains</h3>
                    <p style="margin: 0.25rem 0 0 0; font-size: 0.875rem; opacity: 0.9;">Desired outcomes and benefits</p>
                </div>
                <div style="padding: 1.5rem;">
                    @forelse($project->customer_gains ?? [] as $gain)
                        <div style="background: #ecfdf5; border-left: 3px solid #10b981; padding: 0.875rem 1rem; margin-bottom: 0.75rem; border-radius: 0 8px 8px 0; font-size: 0.95rem; color: #065f46; line-height: 1.5;">
                            {{ $gain }}
                        </div>
                    @empty
                        <p style="color: #9ca3af; text-align: center;">No gains defined</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- AI Reasoning -->
        @if($project->ai_reasoning)
        <div style="background: #ffffff; border-radius: 16px; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-top: 1.5rem;">
            <details>
                <summary style="cursor: pointer; font-weight: 600; color: #1f2937; font-size: 1rem;">
                     AI Analysis Reasoning
                </summary>
                <div style="margin-top: 1rem; padding: 1rem; background: #f9fafb; border-radius: 8px; color: #4b5563; line-height: 1.6; font-size: 0.95rem;">
                    {{ $project->ai_reasoning }}
                </div>
            </details>
        </div>
        @endif

        <div style="margin-top: 1.5rem; display: flex; gap: 1rem; justify-content: center;">
            <a href="{{ route('projects.customer-profile', $project->id) }}" class="btn" style="background: #7CB342; color: #ffffff;">
                ✏️ Edit Customer Profile
            </a>
            <form method="POST" action="{{ route('projects.customer-profile.regenerate', $project->id) }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn" style="background: #f59e0b; color: #ffffff;" onclick="return confirm('Regenerate will overwrite current profile. Continue?');">
                    🔄 Regenerate with AI
                </button>
            </form>
        </div>
        @else
        <div style="background: #ffffff; border-radius: 16px; padding: 3rem; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <svg style="width: 64px; height: 64px; margin: 0 auto 1rem; color: #d1d5db;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <h3 style="color: #1f2937; font-size: 1.25rem; margin-bottom: 0.5rem;">No Customer Profile Yet</h3>
            <p style="color: #6b7280; margin-bottom: 1.5rem;">Complete the empathy map first to generate a customer profile with AI.</p>
            @if($project->empathy_map_completed)
                <a href="{{ route('projects.customer-profile', $project->id) }}" class="btn btn-primary">
                    Generate Customer Profile
                </a>
            @else
                <a href="{{ route('projects.empathy-map', $project->id) }}" class="btn btn-primary">
                    Start with Empathy Map
                </a>
            @endif
        </div>
        @endif
    </div>
</div>

<style>
.tab-btn {
    padding: 0.875rem 1.5rem;
    background: transparent;
    border: none;
    border-bottom: 3px solid transparent;
    color: #6b7280;
    font-size: 0.95rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    margin-bottom: -2px;
}

.tab-btn:hover {
    color: #1f2937;
    background: #f9fafb;
}

.tab-btn.active {
    color: #7CB342;
    border-bottom-color: #7CB342;
}

.form-label {
    display: block;
    font-weight: 500;
    color: #374151;
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
}

.form-input {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    font-size: 0.95rem;
    transition: all 0.2s;
    background: #ffffff;
}

.form-input:focus {
    outline: none;
    border-color: #7CB342;
    box-shadow: 0 0 0 3px rgba(124, 179, 66, 0.1);
}

.empathy-card:hover, .profile-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    transition: all 0.2s;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 12px;
    font-size: 0.95rem;
    font-weight: 500;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.2s;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.btn-primary {
    background: linear-gradient(135deg, #7CB342 0%, #558B2F 100%);
    color: #ffffff;
}
</style>

<script>
function switchTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.style.display = 'none';
    });
    
    // Remove active class from all buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Show selected tab
    document.getElementById('tab-' + tabName).style.display = 'block';
    
    // Add active class to clicked button
    document.querySelector(`[data-tab="${tabName}"]`).classList.add('active');
}
</script>
@endsection
