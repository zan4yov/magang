@extends('layouts.app')

@section('title', 'Empathy Map - Step 2 - IGMS')

@section('content')
<div class="dashboard-container">
    <!-- Progress Indicator -->
    <div class="wizard-progress">
        <div class="wizard-step completed">
            <div class="wizard-step-number">✓</div>
            <div class="wizard-step-label">Project Info</div>
        </div>
        <div class="wizard-step-line active"></div>
        <div class="wizard-step active">
            <div class="wizard-step-number">2</div>
            <div class="wizard-step-label">Empathy Map</div>
        </div>
        <div class="wizard-step-line"></div>
        <div class="wizard-step">
            <div class="wizard-step-number">3</div>
            <div class="wizard-step-label">Customer Profile</div>
        </div>
    </div>

    <!-- Header -->
    <div style="margin: 2rem 0;">
        <h1 class="dashboard-title">Step 2: Empathy Map</h1>
        <p class="dashboard-subtitle">Map what your customers say, think, do, and feel</p>
    </div>

    <div style="max-width: 900px; margin: 0 auto;">
        <div style="background: #ffffff; border-radius: 16px; padding: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <!-- Info Alert -->
            <div style="background: #e0f2f1; border-left: 4px solid #00897b; padding: 1rem; border-radius: 8px; margin-bottom: 2rem;">
                <div style="display: flex; gap: 0.75rem;">
                    <svg style="width: 20px; height: 20px; color: #00695c; flex-shrink: 0; margin-top: 2px;" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div style="color: #00695c; font-size: 0.9375rem;">
                        <strong>Focus on observations:</strong> What you see and hear from customers, not your own assumptions or solutions.
                    </div>
                </div>
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

            <form method="POST" action="{{ route('projects.empathy-map.store', $project->id) }}" id="empathyForm">
                @csrf

                <!-- Says Section -->
                <div class="empathy-section">
                    <div class="empathy-section-header">
                        <h3>💬 Says</h3>
                        <p>What do customers express verbally? Direct quotes and statements.</p>
                    </div>
                    <div id="says-container" class="empathy-items">
                        @if(old('says'))
                            @foreach(old('says') as $index => $item)
                                <div class="empathy-item">
                                    <input type="text" name="says[]" value="{{ $item }}" class="form-input" placeholder='e.g., "I need faster processing times"' required>
                                    <button type="button" class="btn-remove" onclick="removeItem(this)">×</button>
                                </div>
                            @endforeach
                        @else
                            <div class="empathy-item">
                                <input type="text" name="says[]" class="form-input" placeholder='e.g., "I need faster processing times"' required>
                                <button type="button" class="btn-remove" onclick="removeItem(this)">×</button>
                            </div>
                        @endif
                    </div>
                    <button type="button" class="btn-add" onclick="addItem('says')">+ Add Another</button>
                </div>

                <!-- Thinks Section -->
                <div class="empathy-section">
                    <div class="empathy-section-header">
                        <h3>💭 Thinks</h3>
                        <p>What concerns or thoughts might customers have internally?</p>
                    </div>
                    <div id="thinks-container" class="empathy-items">
                        @if(old('thinks'))
                            @foreach(old('thinks') as $index => $item)
                                <div class="empathy-item">
                                    <input type="text" name="thinks[]" value="{{ $item }}" class="form-input" placeholder='e.g., "This might be too expensive"' required>
                                    <button type="button" class="btn-remove" onclick="removeItem(this)">×</button>
                                </div>
                            @endforeach
                        @else
                            <div class="empathy-item">
                                <input type="text" name="thinks[]" class="form-input" placeholder='e.g., "This might be too expensive"' required>
                                <button type="button" class="btn-remove" onclick="removeItem(this)">×</button>
                            </div>
                        @endif
                    </div>
                    <button type="button" class="btn-add" onclick="addItem('thinks')">+ Add Another</button>
                </div>

                <!-- Does Section -->
                <div class="empathy-section">
                    <div class="empathy-section-header">
                        <h3>👤 Does</h3>
                        <p>What observable behaviors or actions do customers take?</p>
                    </div>
                    <div id="does-container" class="empathy-items">
                        @if(old('does'))
                            @foreach(old('does') as $index => $item)
                                <div class="empathy-item">
                                    <input type="text" name="does[]" value="{{ $item }}" class="form-input" placeholder='e.g., "Compares multiple solutions before deciding"' required>
                                    <button type="button" class="btn-remove" onclick="removeItem(this)">×</button>
                                </div>
                            @endforeach
                        @else
                            <div class="empathy-item">
                                <input type="text" name="does[]" class="form-input" placeholder='e.g., "Compares multiple solutions before deciding"' required>
                                <button type="button" class="btn-remove" onclick="removeItem(this)">×</button>
                            </div>
                        @endif
                    </div>
                    <button type="button" class="btn-add" onclick="addItem('does')">+ Add Another</button>
                </div>

                <!-- Feels Section -->
                <div class="empathy-section">
                    <div class="empathy-section-header">
                        <h3>❤️ Feels</h3>
                        <p>What emotions or feelings do customers experience?</p>
                    </div>
                    <div id="feels-container" class="empathy-items">
                        @if(old('feels'))
                            @foreach(old('feels') as $index => $item)
                                <div class="empathy-item">
                                    <input type="text" name="feels[]" value="{{ $item }}" class="form-input" placeholder='e.g., "Frustrated with current inefficiencies"' required>
                                    <button type="button" class="btn-remove" onclick="removeItem(this)">×</button>
                                </div>
                            @endforeach
                        @else
                            <div class="empathy-item">
                                <input type="text" name="feels[]" class="form-input" placeholder='e.g., "Frustrated with current inefficiencies"' required>
                                <button type="button" class="btn-remove" onclick="removeItem(this)">×</button>
                            </div>
                        @endif
                    </div>
                    <button type="button" class="btn-add" onclick="addItem('feels')">+ Add Another</button>
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 2.5rem; padding-top: 2rem; border-top: 1px solid #e5e7eb;">
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <span>Generate Customer Profile with AI</span>
                        <svg style="width: 18px; height: 18px;" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                    <a href="{{ route('projects.index') }}" class="btn" style="background: #e5e7eb; color: #374151;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.wizard-progress {
    display: flex;
    align-items: center;
    justify-content: center;
    max-width: 600px;
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
    font-size: 0.875rem;
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
    margin: 0 1rem;
    max-width: 100px;
}

.wizard-step-line.active {
    background: #7CB342;
}

.empathy-section {
    margin-bottom: 2.5rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid #e5e7eb;
}

.empathy-section:last-of-type {
    border-bottom: none;
}

.empathy-section-header h3 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0 0 0.5rem 0;
}

.empathy-section-header p {
    color: #6b7280;
    font-size: 0.9375rem;
    margin: 0 0 1rem 0;
}

.empathy-items {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.empathy-item {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.btn-remove {
    width: 32px;
    height: 32px;
    flex-shrink: 0;
    background: #fee2e2;
    color: #ef4444;
    border: none;
    border-radius: 6px;
    font-size: 1.5rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    line-height: 1;
}

.btn-remove:hover {
    background: #ef4444;
    color: #ffffff;
}

.btn-add {
    background: #f3f4f6;
    color: #6b7280;
    border: 1px dashed #d1d5db;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-add:hover {
    background: #e8f5e9;
    border-color: #7CB342;
    color: #7CB342;
}
</style>

<script>
function addItem(section) {
    const container = document.getElementById(`${section}-container`);
    const newItem = document.createElement('div');
    newItem.className = 'empathy-item';
    newItem.innerHTML = `
        <input type="text" name="${section}[]" class="form-input" placeholder="Enter ${section} observation..." required>
        <button type="button" class="btn-remove" onclick="removeItem(this)">×</button>
    `;
    container.appendChild(newItem);
}

function removeItem(button) {
    const container = button.closest('.empathy-items');
    const items = container.querySelectorAll('.empathy-item');
    
    // Don't remove if it's the last item
    if (items.length > 1) {
        button.closest('.empathy-item').remove();
    } else {
        alert('You must have at least one item in each category');
    }
}

// Show loading state on submit
document.getElementById('empathyForm').addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<span>Analyzing with AI...</span>';
});
</script>
@endsection
