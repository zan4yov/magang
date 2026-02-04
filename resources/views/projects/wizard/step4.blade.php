@extends('layouts.app')

@section('title', 'Value Map - Step 4 - IGMS')

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
        <div class="wizard-step-line completed"></div>
        <div class="wizard-step completed">
            <div class="wizard-step-number">✓</div>
            <div class="wizard-step-label">Customer Profile</div>
        </div>
        <div class="wizard-step-line {{ $project->hasValueMap() ? 'completed' : 'active' }}"></div>
        <div class="wizard-step {{ $project->hasValueMap() ? 'completed' : 'active' }}">
            <div class="wizard-step-number">{{ $project->hasValueMap() ? '✓' : '4' }}</div>
            <div class="wizard-step-label">Value Map</div>
        </div>
    </div>

    <!-- Header -->
    <div style="margin: 2rem 0;">
        <h1 class="dashboard-title">Step 4: Value Map (AI Generated)</h1>
        <p class="dashboard-subtitle">Value Proposition Canvas - Value Proposition Side</p>
    </div>

    <!-- Status Badge -->
    @php $status = $project->getStatusDetails(); @endphp
    <div style="margin-bottom: 1.5rem;">
        <span style="background: {{ $status['color'] }}20; color: {{ $status['color'] }}; padding: 0.5rem 1rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 600;">
            {{ $status['label'] }}: {{ $status['description'] }}
        </span>
    </div>

    <!-- Error Display -->
    @if($errors->any())
        <div style="background: #fee2e2; border-left: 4px solid #ef4444; padding: 1rem; border-radius: 8px; margin-bottom: 2rem;">
            <div style="color: #991b1b; font-weight: 600; margin-bottom: 0.5rem;">⚠️ Error:</div>
            <ul style="margin: 0; padding-left: 1.5rem; color: #991b1b;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Success Message -->
    @if(session('success'))
        <div style="background: #d1fae5; border: 1px solid #10b981; border-radius: 12px; padding: 1rem 1.5rem; margin-bottom: 2rem; display: flex; align-items: center; gap: 1rem;">
            <svg style="width: 20px; height: 20px; color: #059669;" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span style="color: #065f46; font-weight: 500;">{{ session('success') }}</span>
        </div>
    @endif

    @if(!$project->hasValueMap())
        <!-- Generate Value Map Section -->
        <div style="background: #ffffff; border-radius: 16px; padding: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 2rem; text-align: center;">
            <div style="margin-bottom: 1.5rem;">
                <svg style="width: 64px; height: 64px; color: #7CB342; margin: 0 auto 1rem;" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                <h2 style="font-size: 1.5rem; font-weight: 600; color: #1f2937; margin-bottom: 0.5rem;">Ready to Generate Value Map</h2>
                <p style="color: #6b7280; max-width: 500px; margin: 0 auto;">
                    The AI will analyze your approved Customer Profile using ReAct reasoning to create a matching Value Proposition.
                </p>
            </div>
            
            <form method="POST" action="{{ route('projects.value-map.generate', $project->id) }}" id="generateForm">
                @csrf
                <button type="submit" class="btn btn-primary" id="generateBtn" style="font-size: 1rem; padding: 0.875rem 2rem;">
                    <svg style="width: 20px; height: 20px;" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                    </svg>
                    <span>Generate Value Map with ReAct AI</span>
                </button>
            </form>
        </div>
    @else
        <!-- Value Map Display -->
        <div style="max-width: 1100px; margin: 0 auto;">
            <!-- Value Map Sections -->
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
                <!-- Products & Services -->
                <div class="profile-section">
                    <div class="profile-section-header" style="background: #7c3aed; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <h3>📦 Products & Services</h3>
                            <p>Solutions that enable customer jobs</p>
                        </div>
                        <button onclick="addItem('products_services')" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; padding: 0.5rem 1rem; border-radius: 6px; cursor: pointer; font-size: 0.875rem; font-weight: 500;">
                            + Add
                        </button>
                    </div>
                    <div class="profile-items">
                        @forelse($valueMap['products_services'] ?? [] as $index => $item)
                            <div class="profile-item">
                                <div class="profile-item-text">{{ $item }}</div>
                                <div class="profile-item-actions">
                                    <button onclick="editItem('products_services', {{ $index }}, '{{ addslashes($item) }}')" class="item-action-btn item-edit">
                                        <svg fill="currentColor" viewBox="0 0 20 20" style="width: 14px; height: 14px;">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                        </svg>
                                    </button>
                                    <button onclick="deleteItem('products_services', {{ $index }})" class="item-action-btn item-delete">
                                        <svg fill="currentColor" viewBox="0 0 20 20" style="width: 14px; height: 14px;">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="profile-item-empty">No products/services identified. Click "+ Add" to add manually.</div>
                        @endforelse
                    </div>
                </div>

                <!-- Pain Relievers -->
                <div class="profile-section">
                    <div class="profile-section-header" style="background: #ef4444; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <h3>💊 Pain Relievers</h3>
                            <p>How we eliminate or reduce pains</p>
                        </div>
                        <button onclick="addItem('pain_relievers')" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; padding: 0.5rem 1rem; border-radius: 6px; cursor: pointer; font-size: 0.875rem; font-weight: 500;">
                            + Add
                        </button>
                    </div>
                    <div class="profile-items">
                        @forelse($valueMap['pain_relievers'] ?? [] as $index => $item)
                            <div class="profile-item">
                                <div class="profile-item-text">{{ $item }}</div>
                                <div class="profile-item-actions">
                                    <button onclick="editItem('pain_relievers', {{ $index }}, '{{ addslashes($item) }}')" class="item-action-btn item-edit">
                                        <svg fill="currentColor" viewBox="0 0 20 20" style="width: 14px; height: 14px;">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                        </svg>
                                    </button>
                                    <button onclick="deleteItem('pain_relievers', {{ $index }})" class="item-action-btn item-delete">
                                        <svg fill="currentColor" viewBox="0 0 20 20" style="width: 14px; height: 14px;">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="profile-item-empty">No pain relievers identified. Click "+ Add" to add manually.</div>
                        @endforelse
                    </div>
                </div>

                <!-- Gain Creators -->
                <div class="profile-section">
                    <div class="profile-section-header" style="background: #10b981; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <h3>🚀 Gain Creators</h3>
                            <p>How we create expected gains</p>
                        </div>
                        <button onclick="addItem('gain_creators')" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; padding: 0.5rem 1rem; border-radius: 6px; cursor: pointer; font-size: 0.875rem; font-weight: 500;">
                            + Add
                        </button>
                    </div>
                    <div class="profile-items">
                        @forelse($valueMap['gain_creators'] ?? [] as $index => $item)
                            <div class="profile-item">
                                <div class="profile-item-text">{{ $item }}</div>
                                <div class="profile-item-actions">
                                    <button onclick="editItem('gain_creators', {{ $index }}, '{{ addslashes($item) }}')" class="item-action-btn item-edit">
                                        <svg fill="currentColor" viewBox="0 0 20 20" style="width: 14px; height: 14px;">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                        </svg>
                                    </button>
                                    <button onclick="deleteItem('gain_creators', {{ $index }})" class="item-action-btn item-delete">
                                        <svg fill="currentColor" viewBox="0 0 20 20" style="width: 14px; height: 14px;">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="profile-item-empty">No gain creators identified. Click "+ Add" to add manually.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- AI Reasoning (Layer 2) -->
            <div style="background: #ffffff; border-radius: 16px; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 2rem;">
                <details>
                    <summary style="cursor: pointer; font-weight: 600; color: #1f2937; font-size: 1.0625rem;">
                        🧠 View ReAct Reasoning Trace (Layer 2: Customer Profile → Value Map)
                    </summary>
                    <div style="margin-top: 1rem;">
                        @if(!empty($valueMap['reasoning_trace']))
                            @foreach($valueMap['reasoning_trace'] as $step)
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
                            <div style="color: #6b7280; padding: 1rem;">No reasoning trace available</div>
                        @endif
                    </div>
                </details>
            </div>
        </div>
    @endif

    <!-- Actions -->
    <div style="display: flex; gap: 1rem; justify-content: space-between; max-width: 1100px; margin: 0 auto;">
        <div style="display: flex; gap: 1rem;">
            @if($project->hasValueMap())
                <form method="POST" action="{{ route('projects.value-map.regenerate', $project->id) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn" style="background: #f59e0b; color: #ffffff;">
                        <svg style="width: 18px; height: 18px;" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                        </svg>
                        <span>Regenerate with AI</span>
                    </button>
                </form>
            @endif
            
            <form method="POST" action="{{ route('projects.stepback.customer-profile', $project->id) }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn" style="background: #e5e7eb; color: #374151;">
                    <svg style="width: 18px; height: 18px;" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                    </svg>
                    <span>Step Back to Customer Profile</span>
                </button>
            </form>
        </div>

        <div style="display: flex; gap: 1rem;">
            @if($project->hasValueMap())
                <a href="{{ route('projects.show', $project->id) }}" class="btn btn-primary">
                    <svg style="width: 18px; height: 18px;" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span>View Complete Project</span>
                </a>
            @endif
        </div>
    </div>
</div>

<!-- Hidden forms for edit/delete actions -->
<form id="editForm" method="POST" action="{{ route('projects.value-map.update-item', $project->id) }}" style="display: none;">
    @csrf
    @method('PUT')
    <input type="hidden" name="type" id="editType">
    <input type="hidden" name="index" id="editIndex">
    <input type="hidden" name="value" id="editValue">
</form>

<form id="deleteForm" method="POST" action="{{ route('projects.value-map.delete-item', $project->id) }}" style="display: none;">
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
// Show loading state on generate
document.getElementById('generateForm')?.addEventListener('submit', function() {
    const btn = document.getElementById('generateBtn');
    btn.disabled = true;
    btn.innerHTML = '<span>Generating with ReAct AI...</span>';
});

function addItem(type) {
    const newValue = prompt('Enter new item:');
    if (newValue && newValue.trim()) {
        document.getElementById('editType').value = type;
        document.getElementById('editIndex').value = -1;
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
