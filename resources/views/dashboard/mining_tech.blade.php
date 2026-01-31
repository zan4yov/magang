@extends('layouts.app')

@section('title', 'Mining Technology Team Dashboard - IGMS')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-welcome">
        <h1 class="dashboard-title">Welcome, {{ $user->name }}!</h1>
        <p class="dashboard-subtitle">Mining Technology Team Dashboard</p>
    </div>

    <div class="dashboard-grid">
        <div class="dashboard-card">
            <div class="card-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <h3 class="card-title">All Projects</h3>
            <p class="card-description">View and manage all geological projects</p>
            <a href="#" class="card-link">Manage Projects →</a>
        </div>

        <div class="dashboard-card">
            <div class="card-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                </svg>
            </div>
            <h3 class="card-title">Technical Analysis</h3>
            <p class="card-description">Advanced geological and mining analysis</p>
            <a href="#" class="card-link">Open Analysis →</a>
        </div>

        <div class="dashboard-card">
            <div class="card-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h3 class="card-title">Reports & Data</h3>
            <p class="card-description">Generate and review technical reports</p>
            <a href="#" class="card-link">View Reports →</a>
        </div>

        <div class="dashboard-card">
            <div class="card-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <h3 class="card-title">Team Collaboration</h3>
            <p class="card-description">Collaborate with users and admins</p>
            <a href="#" class="card-link">Team Tools →</a>
        </div>

        <div class="dashboard-card">
            <div class="card-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <h3 class="card-title">Technical Settings</h3>
            <p class="card-description">Configure mining technology parameters</p>
            <a href="#" class="card-link">Settings →</a>
        </div>

        <div class="dashboard-card">
            <div class="card-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h3 class="card-title">Documentation</h3>
            <p class="card-description">Technical guides and specifications</p>
            <a href="#" class="card-link">Read Docs →</a>
        </div>
    </div>

    <div class="dashboard-info">
        <div class="info-card">
            <h3 class="info-title">Your Role</h3>
            <p class="role-badge role-mining-tech">Mining Technology Team</p>
            <p class="info-text">You have advanced access to technical analysis tools, project management, and team collaboration features.</p>
        </div>
    </div>
</div>
@endsection
