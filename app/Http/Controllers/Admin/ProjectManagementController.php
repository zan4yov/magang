<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectManagementController extends Controller
{
    /**
     * Display all projects with filtering and search capabilities
     */
    public function index(Request $request)
    {
        $query = Project::with('user')->withTrashed();

        // Filter by status
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'draft':
                    $query->where('is_draft', true);
                    break;
                case 'completed':
                    $query->where('is_draft', false);
                    break;
                case 'trashed':
                    $query->whereNotNull('deleted_at');
                    break;
            }
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search by title or owner name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Get counts for statistics
        $totalProjects = Project::withTrashed()->count();
        $draftProjects = Project::where('is_draft', true)->count();
        $completedProjects = Project::where('is_draft', false)->count();

        // Paginate results
        $projects = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.projects.index', compact(
            'projects',
            'totalProjects',
            'draftProjects',
            'completedProjects'
        ));
    }

    /**
     * Display the specified project (read-only view for super admin)
     */
    public function show(string $id)
    {
        $project = Project::withTrashed()->with(['user', 'sharedWith'])->findOrFail($id);
        
        return view('admin.projects.show', compact('project'));
    }
}
