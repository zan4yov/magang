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
        <div class="wizard-step-line active"></div>
        <div class="wizard-step completed">
            <div class="wizard-step-number">✓</div>
            <div class="wizard-step-label">Empathy Map</div>
        </div>
        <div class="wizard-step-line active"></div>
        <div class="wizard-step active">
            <div class="wizard-step-number">3</div>
            <div class="wizard-step-label">Customer Profile</div>
        </div>
    </div>

    <!-- Header -->
    <div style="margin: 2rem 0;">
        <h1 class="dashboard-title">Step 3: Customer Profile (AI Generated)</h1>
        <p class="dashboard-subtitle">Value Proposition Canvas - Customer Segment</p>
    </div>

    <div style="max-width: 1100px; margin: 0 auto;">
        <!-- Customer Profile Sections -->
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
            <!-- Customer Jobs -->
            <div class="profile-section">
                <div class="profile-section-header" style="background: #3b82f6;">
                    <h3>⚙️ Customer Jobs</h3>
                    <p>What customers are trying to accomplish</p>
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
                        <div class="profile-item-empty">No jobs identified</div>
                    @endforelse
                </div>
            </div>

            <!-- Customer Pains -->
            <div class="profile-section">
                <div class="profile-section-header" style="background: #ef4444;">
                    <h3>😰 Pains</h3>
                    <p>Obstacles and frustrations</p>
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
                        <div class="profile-item-empty">No pains identified</div>
                    @endforelse
                </div>
            </div>

            <!-- Customer Gains -->
            <div class="profile-section">
                <div class="profile-section-header" style="background: #10b981;">
                    <h3>😊 Gains</h3>
                    <p>Desired outcomes and benefits</p>
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
                        <div class="profile-item-empty">No gains identified</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- AI Reasoning -->
        <div style="background: #ffffff; border-radius: 16px; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 2rem;">
            <details>
                <summary style="cursor: pointer; font-weight: 600; color: #1f2937; font-size: 1.0625rem;">
                    🤖 View AI Reasoning
                </summary>
                <div style="margin-top: 1rem; padding: 1rem; background: #f9fafb; border-radius: 8px; color: #4b5563; line-height: 1.6;">
                    {{ $customerProfile['reasoning'] ?? 'No reasoning available' }}
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
                <form method="POST" action="{{ route('projects.finalize', $project->id) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <svg style="width: 18px; height: 18px;" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span>Complete Project</span>
                    </button>
                </form>
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
@import url('{{ asset('css/wizard-progress.css') }}');

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
