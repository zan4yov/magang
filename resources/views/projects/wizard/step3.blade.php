@extends('layouts.app')

@section('title', 'Customer Profile - Step 3 - IGMS')

@section('content')
<div class="dashboard-container">
    <!-- Progress Indicator -->
    <div class="wizard-progress">
        <div class="wizard-step completed">
            <div class="wizard-step-number">✓</div>
            <div class="wizard-step-label">Project Info</div>
        </div>
        <div class="wizard-step-line completed"></div>
        <div class="wizard-step completed">
            <div class="wizard-step-number">✓</div>
            <div class="wizard-step-label">Empathy Map</div>
        </div>
        <div class="wizard-step-line active"></div>
        <div class="wizard-step active">
            <div class="wizard-step-number">3</div>
            <div class="wizard-step-label">Customer Profile</div>
        </div>
        <div class="wizard-step-line"></div>
        <div class="wizard-step">
            <div class="wizard-step-number">4</div>
            <div class="wizard-step-label">Value Map</div>
        </div>
    </div>

    <!-- Header -->
    <div style="margin: 2rem 0;">
        <h1 class="dashboard-title">Step 3: Customer Profile (AI Generated)</h1>
        <p class="dashboard-subtitle">Value Proposition Canvas - Customer Segment</p>
    </div>

    <!-- Status Badge -->
    @php $status = $project->getStatusDetails(); @endphp
    <div style="margin-bottom: 1.5rem; display: flex; gap: 1rem; align-items: center;">
        <span style="background: {{ $status['color'] }}20; color: {{ $status['color'] }}; padding: 0.5rem 1rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 600;">
            {{ $status['label'] }}
        </span>
        @if($project->customer_profile_approved)
            <span style="background: #d1fae5; color: #059669; padding: 0.5rem 1rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 600;">
                ✓ Approved for Value Map
            </span>
        @else
            <span style="background: #fef3c7; color: #d97706; padding: 0.5rem 1rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 600;">
                ⏳ Awaiting Approval
            </span>
        @endif
    </div>

    <!-- Warning for AI Failure -->
    @if(session('warning') || session('ai_failed'))
        <div style="background: #fff3cd; border: 1px solid #ffc107; border-radius: 12px; padding: 1rem 1.5rem; margin-bottom: 2rem; display: flex; align-items: start; gap: 1rem;">
            <svg style="width: 24px; height: 24px; color: #ff9800; flex-shrink: 0; margin-top: 0.125rem;" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <div style="flex: 1;">
                <div style="font-weight: 600; color: #f57c00; margin-bottom: 0.25rem;">AI Generation Issue</div>
                <div style="color: #666; font-size: 0.95rem; line-height: 1.5;">
                    {{ session('warning') ?? 'AI generation encountered an issue. You can manually add items using the "+ Add" buttons below, edit the empathy map, or try regenerating the profile.' }}
                </div>
            </div>
        </div>
    @endif

    <!-- Success Message -->
    @if(session('success'))
        <div style="background: #d1fae5; border: 1px solid: #10b981; border-radius: 12px; padding: 1rem 1.5rem; margin-bottom: 2rem; display: flex; align-items: center; gap: 1rem;">
            <svg style="width: 20px; height: 20px; color: #059669;" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span style="color: #065f46; font-weight: 500;">{{ session('success') }}</span>
        </div>
    @endif

    <div style="max-width: 1100px; margin: 0 auto;">
        <!-- Customer Profile Sections -->
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
            <!-- Customer Jobs -->
            <div class="profile-section">
                <div class="profile-section-header" style="background: #3b82f6; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h3> Customer Jobs</h3>
                        <p>What customers are trying to accomplish</p>
                    </div>
                    <button onclick="addItem('customer_jobs')" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; padding: 0.5rem 1rem; border-radius: 6px; cursor: pointer; font-size: 0.875rem; font-weight: 500; transition: all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                        + Add
                    </button>
                </div>
                <div class="profile-items">
                    @forelse($customerProfile['customer_jobs'] ?? [] as $index => $job)
                        <div class="profile-item" data-type="customer_jobs" data-index="{{ $index }}">
                            <div class="profile-item-text">{{ $job }}</div>
                            <div class="profile-item-actions">
                                <button onclick="editItem('customer_jobs', {{ $index }}, '{{ addslashes($job) }}')" class="item-action-btn item-edit">
                                    <svg fill="currentColor" viewBox="0 0 20 20" style="width: 14px; height: 14px;">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                    </svg>
                                </button>
                                <button onclick="deleteItem('customer_jobs', {{ $index }})" class="item-action-btn item-delete">
                                    <svg fill="currentColor" viewBox="0 0 20 20" style="width: 14px; height: 14px;">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="profile-item-empty">No jobs identified. Click "+ Add" to add manually.</div>
                    @endforelse
                </div>
            </div>

            <!-- Customer Pains -->
            <div class="profile-section">
                <div class="profile-section-header" style="background: #ef4444; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h3> Pains</h3>
                        <p>Obstacles and frustrations</p>
                    </div>
                    <button onclick="addItem('customer_pains')" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; padding: 0.5rem 1rem; border-radius: 6px; cursor: pointer; font-size: 0.875rem; font-weight: 500; transition: all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                        + Add
                    </button>
                </div>
                <div class="profile-items">
                    @forelse($customerProfile['customer_pains'] ?? [] as $index => $pain)
                        <div class="profile-item" data-type="customer_pains" data-index="{{ $index }}">
                            <div class="profile-item-text">{{ $pain }}</div>
                            <div class="profile-item-actions">
                                <button onclick="editItem('customer_pains', {{ $index }}, '{{ addslashes($pain) }}')" class="item-action-btn item-edit">
                                    <svg fill="currentColor" viewBox="0 0 20 20" style="width: 14px; height: 14px;">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                    </svg>
                                </button>
                                <button onclick="deleteItem('customer_pains', {{ $index }})" class="item-action-btn item-delete">
                                    <svg fill="currentColor" viewBox="0 0 20 20" style="width: 14px; height: 14px;">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="profile-item-empty">No pains identified. Click "+ Add" to add manually.</div>
                    @endforelse
                </div>
            </div>

            <!-- Customer Gains -->
            <div class="profile-section">
                <div class="profile-section-header" style="background: #10b981; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h3> Gains</h3>
                        <p>Desired outcomes and benefits</p>
                    </div>
                    <button onclick="addItem('customer_gains')" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; padding: 0.5rem 1rem; border-radius: 6px; cursor: pointer; font-size: 0.875rem; font-weight: 500; transition: all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                        + Add
                    </button>
                </div>
                <div class="profile-items">
                    @forelse($customerProfile['customer_gains'] ?? [] as $index => $gain)
                        <div class="profile-item" data-type="customer_gains" data-index="{{ $index }}">
                            <div class="profile-item-text">{{ $gain }}</div>
                            <div class="profile-item-actions">
                                <button onclick="editItem('customer_gains', {{ $index }}, '{{ addslashes($gain) }}')" class="item-action-btn item-edit">
                                    <svg fill="currentColor" viewBox="0 0 20 20" style="width: 14px; height: 14px;">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                    </svg>
                                </button>
                                <button onclick="deleteItem('customer_gains', {{ $index }})" class="item-action-btn item-delete">
                                    <svg fill="currentColor" viewBox="0 0 20 20" style="width: 14px; height: 14px;">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="profile-item-empty">No gains identified. Click "+ Add" to add manually.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- AI Reasoning (ReAct Trace) -->
        <div style="background: #ffffff; border-radius: 16px; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 2rem;">
            <details>
                <summary style="cursor: pointer; font-weight: 600; color: #1f2937; font-size: 1.0625rem;">
                    🧠 View ReAct Reasoning Trace (Layer 1: Empathy Map → Customer Profile)
                </summary>
                <div style="margin-top: 1rem;">
                    @if(!empty($customerProfile['reasoning_trace']))
                        @foreach($customerProfile['reasoning_trace'] as $step)
                            <div style="background: #f9fafb; border-radius: 8px; padding: 1rem; margin-bottom: 0.75rem; border-left: 3px solid #7CB342;">
                                <div style="font-weight: 600; color: #1f2937; margin-bottom: 0.5rem;">
                                    Step {{ $step['step'] ?? 'N/A' }}: {{ $step['phase'] ?? 'Analysis' }}
                                </div>
                                <div style="font-size: 0.875rem; color: #4b5563; line-height: 1.6;">
                                    <strong>OBSERVE:</strong> {{ $step['observe'] ?? 'N/A' }}<br>
                                    <strong>THINK:</strong> {{ $step['think'] ?? 'N/A' }}<br>
                                    <strong>ACT:</strong> {{ $step['act'] ?? 'N/A' }}
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div style="padding: 1rem; background: #f9fafb; border-radius: 8px; color: #4b5563; line-height: 1.6;">
                            {{ $customerProfile['reasoning'] ?? 'No reasoning available' }}
                        </div>
                    @endif
                </div>
            </details>
        </div>

        <!-- Actions -->
        <div style="display: flex; gap: 1rem; justify-content: space-between;">
            <form method="POST" action="{{ route('projects.customer-profile.regenerate', $project->id) }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn" style="background: #f59e0b; color: #ffffff;">
                    <svg style="width: 18px; height: 18px;" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                    </svg>
                    <span>Regenerate with AI</span>
                </button>
            </form>

            <div style="display: flex; gap: 1rem;">
                <a href="{{ route('projects.empathy-map', $project->id) }}" class="btn" style="background: #e5e7eb; color: #374151;">
                    <svg style="width: 18px; height: 18px;" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                    </svg>
                    <span>Edit Empathy Map</span>
                </a>
                @if(!$project->customer_profile_approved)
                    <form method="POST" action="{{ route('projects.customer-profile.approve', $project->id) }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <svg style="width: 18px; height: 18px;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span>Approve & Continue to Value Map</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('projects.value-map', $project->id) }}" class="btn btn-primary">
                        <svg style="width: 18px; height: 18px;" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                        <span>Continue to Value Map</span>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Hidden forms for edit/delete actions -->
<form id="editForm" method="POST" action="{{ route('projects.customer-profile.update-item', $project->id) }}" style="display: none;">
    @csrf
    @method('PUT')
    <input type="hidden" name="type" id="editType">
    <input type="hidden" name="index" id="editIndex">
    <input type="hidden" name="value" id="editValue">
</form>

<form id="deleteForm" method="POST" action="{{ route('projects.customer-profile.delete-item', $project->id) }}" style="display: none;">
    @csrf
    @method('DELETE')
    <input type="hidden" name="type" id="deleteType">
    <input type="hidden" name="index" id="deleteIndex">
</form>

<style>
.wizard-progress {
    display: flex;
    align-items: center;
    justify-content: center;
    max-width: 700px;
    margin: 2rem auto 0;
    padding: 1.5rem;
}

.wizard-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
}

.wizard-step-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e5e7eb;
    color: #9ca3af;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1.125rem;
}

.wizard-step.active .wizard-step-number {
    background: #7CB342;
    color: #ffffff;
}

.wizard-step.completed .wizard-step-number {
    background: #7CB342;
    color: #ffffff;
}

.wizard-step-label {
    font-size: 0.75rem;
    color: #6b7280;
    font-weight: 500;
    white-space: nowrap;
}

.wizard-step.active .wizard-step-label,
.wizard-step.completed .wizard-step-label {
    color: #7CB342;
    font-weight: 600;
}

.wizard-step-line {
    flex: 1;
    height: 2px;
    background: #e5e7eb;
    margin: 0 0.75rem;
    max-width: 80px;
}

.wizard-step-line.active,
.wizard-step-line.completed {
    background: #7CB342;
}

.profile-section {
    background: #ffffff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.profile-section-header {
    padding: 1.25rem;
    color: #ffffff;
}

.profile-section-header h3 {
    font-size: 1.125rem;
    font-weight: 600;
    margin: 0 0 0.25rem 0;
}

.profile-section-header p {
    font-size: 0.8125rem;
    margin: 0;
    opacity: 0.9;
}

.profile-items {
    padding: 1rem;
    min-height: 200px;
}

.profile-item {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 0.875rem;
    margin-bottom: 0.75rem;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 0.75rem;
    transition: all 0.2s;
}

.profile-item:hover {
    border-color: #d1d5db;
    background: #ffffff;
}

.profile-item-text {
    flex: 1;
    font-size: 0.9375rem;
    color: #374151;
    line-height: 1.5;
}

.profile-item-actions {
    display: flex;
    gap: 0.25rem;
    flex-shrink: 0;
}

.item-action-btn {
    width: 28px;
    height: 28px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.item-edit {
    background: #e0f2f1;
    color: #00897b;
}

.item-edit:hover {
    background: #00897b;
    color: #ffffff;
}

.item-delete {
    background: #fee2e2;
    color: #ef4444;
}

.item-delete:hover {
    background: #ef4444;
    color: #ffffff;
}

.profile-item-empty {
    text-align: center;
    color: #9ca3af;
    padding: 2rem;
    font-size: 0.9375rem;
}
</style>

<script>
function addItem(type) {
    const newValue = prompt('Enter new item:');
    if (newValue && newValue.trim()) {
        document.getElementById('editType').value = type;
        document.getElementById('editIndex').value = -1; // -1 indicates new item
        document.getElementById('editValue').value = newValue.trim();
        document.getElementById('editForm').submit();
    }
}

function editItem(type, index, currentValue) {
    const newValue = prompt('Edit item:', currentValue);
    if (newValue && newValue !== currentValue) {
        document.getElementById('editType').value = type;
        document.getElementById('editIndex').value = index;
        document.getElementById('editValue').value = newValue;
        document.getElementById('editForm').submit();
    }
}

function deleteItem(type, index) {
    if (confirm('Are you sure you want to delete this item?')) {
        document.getElementById('deleteType').value = type;
        document.getElementById('deleteIndex').value = index;
        document.getElementById('deleteForm').submit();
    }
}
</script>
@endsection
