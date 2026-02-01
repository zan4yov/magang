@extends('layouts.app')

@section('title', 'Create Project - Step 1 - IGMS')

@section('content')
<div class="dashboard-container">
    <!-- Progress Indicator -->
    <div class="wizard-progress">
        <div class="wizard-step active">
            <div class="wizard-step-number">1</div>
            <div class="wizard-step-label">Project Info</div>
        </div>
        <div class="wizard-step-line"></div>
        <div class="wizard-step">
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
        <h1 class="dashboard-title">Step 1: Project Metadata</h1>
        <p class="dashboard-subtitle">Tell us about your project idea</p>
    </div>

    <div style="max-width: 700px; margin: 0 auto;">
        <div style="background: #ffffff; border-radius: 16px; padding: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            @if($errors->any())
                <div class="alert alert-error" style="margin-bottom: 1.5rem;">
                    <ul class="error-list">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('projects.store') }}">
                @csrf

                <div class="form-group">
                    <label for="title" class="form-label">Project Name *</label>
                    <input 
                        type="text" 
                        id="title" 
                        name="title" 
                        value="{{ old('title') }}"
                        class="form-input @error('title') input-error @enderror"
                        placeholder="e.g., Smart Mining Safety System"
                        required
                        autofocus
                    >
                    @error('title')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Short Description *</label>
                    <textarea 
                        id="description" 
                        name="description" 
                        rows="4"
                        class="form-input @error('description') input-error @enderror"
                        placeholder="Briefly describe what this project aims to accomplish..."
                        required
                    >{{ old('description') }}</textarea>
                    <p class="form-hint">This helps guide the AI analysis in later steps</p>
                    @error('description')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary">
                        <span>Next: Empathy Map</span>
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

.wizard-step-label {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
    white-space: nowrap;
}

.wizard-step.active .wizard-step-label {
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
</style>
@endsection
