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
                'active_users_24h' => \App\Models\User::where('updated_at', '>=', now()->subDay())->count(),
                'users_by_role' => [
                    'user' => \App\Models\User::where('role', 'user')->count(),
                    'mining_tech' => \App\Models\User::where('role', 'mining_tech')->count(),
                    'super_admin' => \App\Models\User::where('role', 'super_admin')->count(),
                ],
            ];

            // Calculate growth percentage
            $totalLastMonth = \App\Models\User::where('created_at', '<', now()->subMonth())->count();
            $stats['growth_percentage'] = $totalLastMonth > 0 
                ? round((($stats['total_users'] - $totalLastMonth) / $totalLastMonth) * 100, 1)
                : 100;

            // Recent users activity (last 10)
            $stats['recent_users'] = \App\Models\User::orderBy('created_at', 'desc')->limit(10)->get();

            return view('dashboard.super_admin', compact('user', 'stats'));
        }

        // Route to role-specific dashboard view
        switch ($user->role) {
            case 'mining_tech':
                // For now, mining tech users see the same as regular users
                return redirect()->route('projects.index');
            case 'user':
            default:
                // Redirect to projects index which handles all the project dashboard logic
                return redirect()->route('projects.index');
        }
    }
}
