<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the appropriate dashboard based on user role
     */
    public function index()
    {
        $user = Auth::user();

        // Collect statistics for super admin
        if ($user->isSuperAdmin()) {
            $stats = [
                'total_users' => \App\Models\User::count(),
                'total_users_last_month' => \App\Models\User::where('created_at', '>=', now()->subMonth())->count(),
                'active_users_now' => \App\Models\User::getOnlineCount(5), // Online in last 5 minutes
                'users_by_role' => [
                    'user' => \App\Models\User::where('role', 'user')->count(),
                    'mining_tech' => \App\Models\User::where('role', 'mining_tech')->count(),
                    'super_admin' => \App\Models\User::where('role', 'super_admin')->count(),
                ],
                // Project statistics
                'total_projects' => \App\Models\Project::withTrashed()->count(),
                'completed_projects' => \App\Models\Project::where('is_draft', false)->count(),
                'draft_projects' => \App\Models\Project::where('is_draft', true)->count(),
                'recent_projects_count' => \App\Models\Project::where('created_at', '>=', now()->subDays(30))->count(),
                'projects_by_category' => [
                    'mine_tech' => \App\Models\Project::where('category', 'mine_tech')->count(),
                    'enviro' => \App\Models\Project::where('category', 'enviro')->count(),
                    'startup' => \App\Models\Project::where('category', 'startup')->count(),
                    'other' => \App\Models\Project::where('category', 'other')->count(),
                ],
            ];

            // Calculate growth percentage
            $totalLastMonth = \App\Models\User::where('created_at', '<', now()->subMonth())->count();
            $stats['growth_percentage'] = $totalLastMonth > 0 
                ? round((($stats['total_users'] - $totalLastMonth) / $totalLastMonth) * 100, 1)
                : 100;

            // Recent users activity (last 10)
            $stats['recent_users'] = \App\Models\User::orderBy('created_at', 'desc')->limit(10)->get();

            // Recent projects (last 10) with creator info
            $stats['recent_projects'] = \App\Models\Project::with('user')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            return view('dashboard.super_admin', compact('user', 'stats'));
        }

        // Mining Tech Team Dashboard
        if ($user->isMiningTech()) {
            // Get all projects in the system
            $allProjects = \App\Models\Project::with('user')
                ->where('is_draft', false)
                ->orderBy('created_at', 'desc')
                ->get();
            
            // Get Mining Tech user's own projects
            $myProjects = \App\Models\Project::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
            
            // Statistics
            $stats = [
                'total_projects' => \App\Models\Project::where('is_draft', false)->count(),
                'my_projects' => $myProjects->count(),
                'recent_projects' => \App\Models\Project::where('is_draft', false)
                    ->where('created_at', '>=', now()->subDays(7))
                    ->count(),
                'by_category' => [
                    'mine_tech' => \App\Models\Project::where('is_draft', false)->where('category', 'mine_tech')->count(),
                    'enviro' => \App\Models\Project::where('is_draft', false)->where('category', 'enviro')->count(),
                    'startup' => \App\Models\Project::where('is_draft', false)->where('category', 'startup')->count(),
                    'other' => \App\Models\Project::where('is_draft', false)->where('category', 'other')->count(),
                ],
            ];
            
            return view('dashboard.mining_tech', compact('user', 'allProjects', 'myProjects', 'stats'));
        }
        
        // Regular user dashboard
        return redirect()->route('projects.index');
    }
}
