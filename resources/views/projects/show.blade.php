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
                @if($project->canEdit(Auth::user()))
                    <a href="{{ route('projects.edit', $project->id) }}" class="btn" style="background: #7CB342; color: #ffffff;">
                        Edit Project
                    </a>
                @endif
                @if($project->user_id === Auth::id())
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

    <!-- Sharing Section -->
    @if($project->user_id === Auth::id())
    <div style="background: #ffffff; border-radius: 16px; padding: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-top: 2rem;">
        <h3 style="color: #1f2937; font-size: 1.25rem; font-weight: 600; margin-bottom: 1.5rem;">Share Project</h3>
        
        <!-- Share Form -->
        <div style="margin-bottom: 2rem;">
            <form id="shareForm" style="display: flex; gap: 0.75rem; align-items: start;">
                @csrf
                <div style="flex: 1;">
                    <input 
                        type="text" 
                        id="shareIdentifier" 
                        placeholder="Enter email or username" 
                        style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 0.95rem; transition: all 0.2s;"
                        onfocus="this.style.borderColor='#7CB342'; this.style.outline='none';"
                        onblur="this.style.borderColor='#e5e7eb';"
                    >
                    <div id="shareError" style="color: #ef4444; font-size: 0.875rem; margin-top: 0.5rem; display: none;"></div>
                    <div id="shareSuccess" style="color: #10b981; font-size: 0.875rem; margin-top: 0.5rem; display: none;"></div>
                </div>
                <div style="display: flex; gap: 0.5rem; align-items: center;">
                    <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.95rem; color: #6b7280; cursor: pointer; user-select: none;">
                        <input type="checkbox" id="canEditCheckbox" style="width: 18px; height: 18px; cursor: pointer;">
                        <span>Can Edit</span>
                    </label>
                    <button 
                        type="submit" 
                        id="shareButton"
                        class="btn" 
                        style="background: #7CB342; color: #ffffff; white-space: nowrap;"
                    >
                        Share
                    </button>
                </div>
            </form>
        </div>

        <!-- Shared Users List -->
        <div>
            <h4 style="color: #6b7280; font-size: 0.875rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 1rem;">
                Shared With (<span id="sharedCount">{{ $sharedUsers->count() }}</span>)
            </h4>
            <div id="sharedUsersList">
                @forelse($sharedUsers as $user)
                <div class="shared-user-item" data-user-id="{{ $user->id }}" style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: #f9fafb; border-radius: 12px; margin-bottom: 0.75rem;">
                    <div style="display: flex; align-items: center; gap: 1rem; flex: 1;">
                        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #7CB342, #558B2F); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #ffffff; font-weight: 600; font-size: 1rem;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div style="flex: 1;">
                            <div style="color: #1f2937; font-weight: 500;">{{ $user->name }}</div>
                            <div style="color: #6b7280; font-size: 0.875rem;">{{ $user->email }}</div>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <button 
                            class="permission-toggle"
                            data-user-id="{{ $user->id }}"
                            data-can-edit="{{ $user->pivot->can_edit ? 'true' : 'false' }}"
                            style="padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.875rem; font-weight: 500; cursor: pointer; border: none; transition: all 0.2s; {{ $user->pivot->can_edit ? 'background: #7CB342; color: #ffffff;' : 'background: #e5e7eb; color: #6b7280;' }}"
                        >
                            {{ $user->pivot->can_edit ? 'Can Edit' : 'View Only' }}
                        </button>
                        <button 
                            class="remove-share"
                            data-user-id="{{ $user->id }}"
                            data-user-name="{{ $user->name }}"
                            style="padding: 0.5rem 0.75rem; background: #fee2e2; color: #991b1b; border: none; border-radius: 8px; cursor: pointer; font-size: 0.875rem; font-weight: 500; transition: all 0.2s;"
                            onmouseover="this.style.background='#fecaca';"
                            onmouseout="this.style.background='#fee2e2';"
                        >
                            Remove
                        </button>
                    </div>
                </div>
                @empty
                <div id="emptyState" style="text-align: center; padding: 2rem; color: #9ca3af;">
                    <svg style="width: 48px; height: 48px; margin: 0 auto 1rem; color: #d1d5db;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <p style="font-size: 0.95rem;">Not shared with anyone yet</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    @endif
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const shareForm = document.getElementById('shareForm');
    const shareIdentifier = document.getElementById('shareIdentifier');
    const canEditCheckbox = document.getElementById('canEditCheckbox');
    const shareButton = document.getElementById('shareButton');
    const shareError = document.getElementById('shareError');
    const shareSuccess = document.getElementById('shareSuccess');
    const sharedUsersList = document.getElementById('sharedUsersList');
    const sharedCount = document.getElementById('sharedCount');
    const projectId = {{ $project->id }};

    // Share project form submission
    if (shareForm) {
        shareForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const identifier = shareIdentifier.value.trim();
            if (!identifier) {
                showError('Please enter an email or username');
                return;
            }

            // Show loading state
            shareButton.disabled = true;
            shareButton.textContent = 'Sharing...';
            hideMessages();

            try {
                const response = await fetch(`/projects/${projectId}/share`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        identifier: identifier,
                        can_edit: canEditCheckbox.checked
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    showSuccess(data.message);
                    shareIdentifier.value = '';
                    canEditCheckbox.checked = false;
                    updateSharedUsersList(data.sharedUsers);
                } else {
                    showError(data.error || 'Failed to share project');
                }
            } catch (error) {
                showError('An error occurred. Please try again.');
                console.error('Share error:', error);
            } finally {
                shareButton.disabled = false;
                shareButton.textContent = 'Share';
            }
        });
    }

    // Permission toggle
    document.addEventListener('click', async function(e) {
        if (e.target.classList.contains('permission-toggle')) {
            const button = e.target;
            const userId = button.dataset.userId;
            const originalText = button.textContent;

            button.disabled = true;
            button.textContent = 'Updating...';

            try {
                const response = await fetch(`/projects/${projectId}/share/${userId}/permission`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    button.dataset.canEdit = data.can_edit ? 'true' : 'false';
                    button.textContent = data.can_edit ? 'Can Edit' : 'View Only';
                    
                    if (data.can_edit) {
                        button.style.background = '#7CB342';
                        button.style.color = '#ffffff';
                    } else {
                        button.style.background = '#e5e7eb';
                        button.style.color = '#6b7280';
                    }
                    
                    showSuccess(data.message);
                } else {
                    showError(data.error || 'Failed to update permission');
                    button.textContent = originalText;
                }
            } catch (error) {
                showError('An error occurred. Please try again.');
                button.textContent = originalText;
                console.error('Permission toggle error:', error);
            } finally {
                button.disabled = false;
            }
        }
    });

    // Remove share
    document.addEventListener('click', async function(e) {
        if (e.target.classList.contains('remove-share')) {
            const button = e.target;
            const userId = button.dataset.userId;
            const userName = button.dataset.userName;

            if (!confirm(`Remove ${userName} from shared users?`)) {
                return;
            }

            button.disabled = true;
            button.textContent = 'Removing...';

            try {
                const response = await fetch(`/projects/${projectId}/share/${userId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    // Remove the user item from DOM
                    const userItem = button.closest('.shared-user-item');
                    userItem.style.transition = 'opacity 0.3s, transform 0.3s';
                    userItem.style.opacity = '0';
                    userItem.style.transform = 'translateX(-20px)';
                    
                    setTimeout(() => {
                        userItem.remove();
                        updateSharedCount(-1);
                        checkEmptyState();
                    }, 300);

                    showSuccess(data.message);
                } else {
                    showError(data.error || 'Failed to remove user');
                    button.disabled = false;
                    button.textContent = 'Remove';
                }
            } catch (error) {
                showError('An error occurred. Please try again.');
                button.disabled = false;
                button.textContent = 'Remove';
                console.error('Remove share error:', error);
            }
        }
    });

    // Helper functions
    function showError(message) {
        shareError.textContent = message;
        shareError.style.display = 'block';
        shareSuccess.style.display = 'none';
    }

    function showSuccess(message) {
        shareSuccess.textContent = message;
        shareSuccess.style.display = 'block';
        shareError.style.display = 'none';
        setTimeout(hideMessages, 5000);
    }

    function hideMessages() {
        shareError.style.display = 'none';
        shareSuccess.style.display = 'none';
    }

    function updateSharedUsersList(users) {
        const emptyState = document.getElementById('emptyState');
        if (emptyState) {
            emptyState.remove();
        }

        users.forEach(user => {
            // Check if user already exists
            if (document.querySelector(`[data-user-id="${user.id}"]`)) {
                return;
            }

            const userItem = createUserItem(user);
            sharedUsersList.appendChild(userItem);
        });

        updateSharedCount(1);
    }

    function createUserItem(user) {
        const div = document.createElement('div');
        div.className = 'shared-user-item';
        div.dataset.userId = user.id;
        div.style.cssText = 'display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: #f9fafb; border-radius: 12px; margin-bottom: 0.75rem;';
        
        const initial = user.name.charAt(0).toUpperCase();
        const permissionStyle = user.can_edit ? 'background: #7CB342; color: #ffffff;' : 'background: #e5e7eb; color: #6b7280;';
        const permissionText = user.can_edit ? 'Can Edit' : 'View Only';
        
        div.innerHTML = `
            <div style="display: flex; align-items: center; gap: 1rem; flex: 1;">
                <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #7CB342, #558B2F); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #ffffff; font-weight: 600; font-size: 1rem;">
                    ${initial}
                </div>
                <div style="flex: 1;">
                    <div style="color: #1f2937; font-weight: 500;">${user.name}</div>
                    <div style="color: #6b7280; font-size: 0.875rem;">${user.email}</div>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <button 
                    class="permission-toggle"
                    data-user-id="${user.id}"
                    data-can-edit="${user.can_edit ? 'true' : 'false'}"
                    style="padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.875rem; font-weight: 500; cursor: pointer; border: none; transition: all 0.2s; ${permissionStyle}"
                >
                    ${permissionText}
                </button>
                <button 
                    class="remove-share"
                    data-user-id="${user.id}"
                    data-user-name="${user.name}"
                    style="padding: 0.5rem 0.75rem; background: #fee2e2; color: #991b1b; border: none; border-radius: 8px; cursor: pointer; font-size: 0.875rem; font-weight: 500; transition: all 0.2s;"
                    onmouseover="this.style.background='#fecaca';"
                    onmouseout="this.style.background='#fee2e2';"
                >
                    Remove
                </button>
            </div>
        `;
        
        return div;
    }

    function updateSharedCount(delta) {
        const currentCount = parseInt(sharedCount.textContent);
        sharedCount.textContent = currentCount + delta;
    }

    function checkEmptyState() {
        const items = sharedUsersList.querySelectorAll('.shared-user-item');
        if (items.length === 0) {
            const emptyState = document.createElement('div');
            emptyState.id = 'emptyState';
            emptyState.style.cssText = 'text-align: center; padding: 2rem; color: #9ca3af;';
            emptyState.innerHTML = `
                <svg style="width: 48px; height: 48px; margin: 0 auto 1rem; color: #d1d5db;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <p style="font-size: 0.95rem;">Not shared with anyone yet</p>
            `;
            sharedUsersList.appendChild(emptyState);
        }
    }
});
</script>
</style>
@endsection
