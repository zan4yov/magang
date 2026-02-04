<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of projects (dashboard view)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Mining Tech users should use their own dashboard
        if ($user->isMiningTech()) {
            return redirect()->route('dashboard');
        }
        
        $tab = $request->get('tab', 'recent'); // recent, personal, shared

        $query = Project::query();

        switch ($tab) {
            case 'recent':
                // Recently viewed projects (owned or shared)
                $query->where(function($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->orWhereHas('sharedWith', function($sq) use ($user) {
                          $sq->where('users.id', $user->id);
                      });
                })->recentlyViewed();
                break;

            case 'personal':
                // Personal projects (owned by user)
                $query->personal($user->id);
                break;

            case 'shared':
                // Shared projects (shared with user)
                $query->shared($user->id);
                break;
        }

        $projects = $query->where('is_draft', false)->get();
        
        // Get counts for sidebar badges
        $draftsCount = Project::where('user_id', $user->id)->drafts()->count();
        $trashedCount = Project::where('user_id', $user->id)->onlyTrashed()->count();
        $starredCount = Project::where('user_id', $user->id)->starred()->count();

        return view('dashboard.user', compact('projects', 'tab', 'draftsCount', 'trashedCount', 'starredCount'));
    }

    /**
     * Show the form for creating a new project
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Store a newly created project
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $project = Auth::user()->projects()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => 'other',
            'color_accent' => 'blue',
            'is_draft' => true, // Keep as draft until wizard is complete
            'last_viewed_at' => now(),
        ]);

        // Redirect to Step 2: Empathy Map
        return redirect()->route('projects.empathy-map', $project->id);
    }

    /**
     * Show empathy map form (Step 2)
     */
    public function showEmpathyMap($id)
    {
        $project = Project::findOrFail($id);
        
        if (!$this->canAccess($project)) {
            abort(403);
        }

        return view('projects.wizard.step2', compact('project'));
    }

    /**
     * Store empathy map and generate customer profile with AI (Step 2 -> 3)
     */
    public function storeEmpathyMap(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        
        if (!$project->canEdit(Auth::user())) {
            abort(403, 'You do not have permission to edit this project.');
        }

        $validated = $request->validate([
            'says' => 'required|array|min:1',
            'says.*' => 'required|string',
            'thinks' => 'required|array|min:1',
            'thinks.*' => 'required|string',
            'does' => 'required|array|min:1',
            'does.*' => 'required|string',
            'feels' => 'required|array|min:1',
            'feels.*' => 'required|string',
        ]);

        // Store empathy map data
        $project->update([
            'empathy_says' => $validated['says'],
            'empathy_thinks' => $validated['thinks'],
            'empathy_does' => $validated['does'],
            'empathy_feels' => $validated['feels'],
            'empathy_map_completed' => true,
        ]);

        // Generate customer profile using AI with ReAct reasoning (MANDATORY)
        try {
            $geminiService = app(\App\Services\GeminiService::class);
            $customerProfile = $geminiService->generateCustomerProfile([
                'says' => $validated['says'],
                'thinks' => $validated['thinks'],
                'does' => $validated['does'],
                'feels' => $validated['feels'],
            ]);

            $project->update([
                'customer_jobs' => $customerProfile['customer_jobs'],
                'customer_pains' => $customerProfile['customer_pains'],
                'customer_gains' => $customerProfile['customer_gains'],
                'ai_reasoning' => $customerProfile['reasoning'] ?? 'AI-generated customer profile with ReAct reasoning',
                'reasoning_layer1' => $customerProfile['reasoning_trace'] ?? [],
                'customer_profile_generated' => true,
                'customer_profile_approved' => false, // Needs user approval
                'project_status' => 'draft', // Still in draft until approved
            ]);

            return redirect()->route('projects.customer-profile', $project->id)
                ->with('success', 'Customer profile generated with ReAct reasoning! Please review and approve to continue.');
                
        } catch (\Exception $e) {
            // AI is mandatory - show error and ask user to retry
            \Log::error('AI VPC generation failed for project ' . $project->id . ': ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->withErrors(['ai_error' => 'AI generation failed: ' . $e->getMessage() . ' Please wait a moment and try again.']);
        }
    }

    /**
     * Show customer profile (Step 3)
     */
    public function showCustomerProfile($id)
    {
        $project = Project::findOrFail($id);
        
        if (!$this->canAccess($project)) {
            abort(403);
        }

        $customerProfile = $project->getCustomerProfileData();

        return view('projects.wizard.step3', compact('project', 'customerProfile'));
    }

    /**
     * Update or add a customer profile item
     */
    public function updateCustomerProfileItem(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        
        // Only users with edit permission can update profile items
        if (!$project->canEdit(Auth::user())) {
            abort(403, 'You do not have permission to edit this project.');
        }

        $validated = $request->validate([
            'type' => 'required|in:customer_jobs,customer_pains,customer_gains',
            'index' => 'required|integer|min:-1',
            'value' => 'required|string',
        ]);

        $data = $project->{$validated['type']} ?? [];
        
        if ($validated['index'] == -1) {
            // Adding a new item
            $data[] = $validated['value'];
            $project->update([
                $validated['type'] => $data,
            ]);
            return back()->with('success', 'Item added successfully');
        } elseif (isset($data[$validated['index']])) {
            // Editing existing item
            $data[$validated['index']] = $validated['value'];
            $project->update([
                $validated['type'] => $data,
            ]);
            return back()->with('success', 'Item updated successfully');
        }

        return back()->with('error', 'Item not found');
    }

    /**
     * Delete a customer profile item
     */
    public function deleteCustomerProfileItem(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        
        // Only users with edit permission can delete profile items
        if (!$project->canEdit(Auth::user())) {
            abort(403, 'You do not have permission to edit this project.');
        }

        $validated = $request->validate([
            'type' => 'required|in:customer_jobs,customer_pains,customer_gains',
            'index' => 'required|integer|min:0',
        ]);

        $data = $project->{$validated['type']} ?? [];
        
        if (isset($data[$validated['index']])) {
            unset($data[$validated['index']]);
            $data = array_values($data); // Re-index array
            $project->update([
                $validated['type'] => $data,
            ]);
        }

        return back()->with('success', 'Item deleted successfully');
    }

    /**
     * Regenerate customer profile with AI
     */
    public function regenerateCustomerProfile($id)
    {
        $project = Project::findOrFail($id);
        
        if (!$project->canEdit(Auth::user())) {
            abort(403, 'You do not have permission to edit this project.');
        }

        try {
            $geminiService = app(\App\Services\GeminiService::class);
            $empathyData = $project->getEmpathyMapData();
            
            $customerProfile = $geminiService->generateCustomerProfile($empathyData);

            $project->update([
                'customer_jobs' => $customerProfile['customer_jobs'],
                'customer_pains' => $customerProfile['customer_pains'],
                'customer_gains' => $customerProfile['customer_gains'],
                'ai_reasoning' => $customerProfile['reasoning'] ?? 'AI-generated customer profile with ReAct reasoning',
                'reasoning_layer1' => $customerProfile['reasoning_trace'] ?? [],
                'customer_profile_approved' => false, // Reset approval on regenerate
            ]);

            return back()->with('success', 'Customer profile regenerated successfully');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'AI generation failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Approve customer profile and immediately generate Value Map
     */
    public function approveCustomerProfile($id)
    {
        $project = Project::findOrFail($id);
        
        if (!$project->canEdit(Auth::user())) {
            abort(403, 'You do not have permission to edit this project.');
        }

        if (!$project->hasCustomerProfile()) {
            return back()->withErrors(['error' => 'Customer profile must be generated before approval.']);
        }

        // 1. Mark as approved
        $project->update([
            'customer_profile_approved' => true,
            'project_status' => 'progress',
        ]);

        // 2. Automatically generate Value Map
        try {
            $geminiService = app(\App\Services\GeminiService::class);
            $valueMap = $geminiService->generateValueMap($project->getCustomerProfileData());

            $project->update([
                'products_services' => $valueMap['products_services'],
                'pain_relievers' => $valueMap['pain_relievers'],
                'gain_creators' => $valueMap['gain_creators'],
                'reasoning_layer2' => $valueMap['reasoning_trace'] ?? [],
                'value_map_generated' => true,
                'project_status' => 'complete',
                'is_draft' => false,
            ]);

            return redirect()->route('projects.value-map', $project->id)
                ->with('success', 'Customer profile approved and Value Map generated successfully!');

        } catch (\Exception $e) {
            // If generation fails, we still approved the profile, but warn the user
            \Log::error('Auto-generation of Value Map failed for project ' . $project->id . ': ' . $e->getMessage());
            
            return redirect()->route('projects.value-map', $project->id)
                ->with('warning', 'Customer profile approved, but auto-generation failed: ' . $e->getMessage() . '. Please try generating manually.');
        }
    }

    /**
     * Show Value Map (Step 4)
     */
    public function showValueMap($id)
    {
        $project = Project::findOrFail($id);
        
        if (!$this->canAccess($project)) {
            abort(403);
        }

        $customerProfile = $project->getCustomerProfileData();
        $valueMap = $project->getValueMapData();

        return view('projects.wizard.step4', compact('project', 'customerProfile', 'valueMap'));
    }

    /**
     * Generate Value Map from approved Customer Profile using ReAct reasoning
     */
    public function generateValueMap($id)
    {
        $project = Project::findOrFail($id);
        
        if (!$project->canEdit(Auth::user())) {
            abort(403, 'You do not have permission to edit this project.');
        }

        if (!$project->canGenerateValueMap()) {
            return back()->withErrors(['error' => 'Customer profile must be approved first.']);
        }

        try {
            $geminiService = app(\App\Services\GeminiService::class);
            $valueMap = $geminiService->generateValueMap($project->getCustomerProfileData());

            $project->update([
                'products_services' => $valueMap['products_services'],
                'pain_relievers' => $valueMap['pain_relievers'],
                'gain_creators' => $valueMap['gain_creators'],
                'reasoning_layer2' => $valueMap['reasoning_trace'] ?? [],
                'value_map_generated' => true,
                'project_status' => 'complete',
                'is_draft' => false,
            ]);

            return redirect()->route('projects.value-map', $project->id)
                ->with('success', 'Value Map generated successfully with ReAct reasoning!');
                
        } catch (\Exception $e) {
            \Log::error('AI Value Map generation failed for project ' . $project->id . ': ' . $e->getMessage());
            
            return back()->withErrors(['ai_error' => 'AI generation failed: ' . $e->getMessage() . ' Please try again.']);
        }
    }

    /**
     * Regenerate Value Map with AI
     */
    public function regenerateValueMap($id)
    {
        $project = Project::findOrFail($id);
        
        if (!$project->canEdit(Auth::user())) {
            abort(403, 'You do not have permission to edit this project.');
        }

        try {
            $geminiService = app(\App\Services\GeminiService::class);
            $valueMap = $geminiService->generateValueMap($project->getCustomerProfileData());

            $project->update([
                'products_services' => $valueMap['products_services'],
                'pain_relievers' => $valueMap['pain_relievers'],
                'gain_creators' => $valueMap['gain_creators'],
                'reasoning_layer2' => $valueMap['reasoning_trace'] ?? [],
            ]);

            return back()->with('success', 'Value Map regenerated successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'AI generation failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Step back from Value Map to Customer Profile
     */
    public function stepBackToCustomerProfile($id)
    {
        $project = Project::findOrFail($id);
        
        if (!$project->canEdit(Auth::user())) {
            abort(403, 'You do not have permission to edit this project.');
        }

        $project->update([
            'customer_profile_approved' => false,
            'project_status' => 'draft',
        ]);

        return redirect()->route('projects.customer-profile', $project->id)
            ->with('info', 'Stepped back to Customer Profile. You can edit and re-approve.');
    }

    /**
     * Update or add a Value Map item
     */
    public function updateValueMapItem(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        
        if (!$project->canEdit(Auth::user())) {
            abort(403, 'You do not have permission to edit this project.');
        }

        $validated = $request->validate([
            'type' => 'required|in:products_services,pain_relievers,gain_creators',
            'index' => 'required|integer|min:-1',
            'value' => 'required|string',
        ]);

        $data = $project->{$validated['type']} ?? [];
        
        if ($validated['index'] == -1) {
            $data[] = $validated['value'];
            $project->update([$validated['type'] => $data]);
            return back()->with('success', 'Item added successfully');
        } elseif (isset($data[$validated['index']])) {
            $data[$validated['index']] = $validated['value'];
            $project->update([$validated['type'] => $data]);
            return back()->with('success', 'Item updated successfully');
        }

        return back()->with('error', 'Item not found');
    }

    /**
     * Delete a Value Map item
     */
    public function deleteValueMapItem(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        
        if (!$project->canEdit(Auth::user())) {
            abort(403, 'You do not have permission to edit this project.');
        }

        $validated = $request->validate([
            'type' => 'required|in:products_services,pain_relievers,gain_creators',
            'index' => 'required|integer|min:0',
        ]);

        $data = $project->{$validated['type']} ?? [];
        
        if (isset($data[$validated['index']])) {
            unset($data[$validated['index']]);
            $data = array_values($data);
            $project->update([$validated['type'] => $data]);
        }

        return back()->with('success', 'Item deleted successfully');
    }

    /**
     * Finalize project (mark as complete, not draft)
     * @deprecated Use approveCustomerProfile + generateValueMap flow instead
     */
    public function finalizeProject($id)
    {
        $project = Project::findOrFail($id);
        
        if (!$project->canEdit(Auth::user())) {
            abort(403, 'You do not have permission to edit this project.');
        }

        $project->update([
            'is_draft' => false,
            'project_status' => 'complete',
        ]);

        return redirect()->route('projects.index')
            ->with('success', 'Project completed successfully!');
    }


    /**
     * Display the specified project
     */
    public function show(string $id)
    {
        $project = Project::findOrFail($id);
        
        // Check access
        if (!$this->canAccess($project)) {
            abort(403, 'Unauthorized access to this project.');
        }

        // Mark as viewed
        $project->markAsViewed();

        // Load shared users with pivot data
        $sharedUsers = $project->sharedWith()->get();

        return view('projects.show', compact('project', 'sharedUsers'));
    }

    /**
     * Show the form for editing the project
     */
    public function edit(string $id)
    {
        $project = Project::findOrFail($id);
        
        // Check edit permission
        if (!$project->canEdit(Auth::user())) {
            abort(403, 'You do not have permission to edit this project.');
        }

        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified project
     */
    public function update(Request $request, string $id)
    {
        $project = Project::findOrFail($id);
        
        // Check edit permission
        if (!$project->canEdit(Auth::user())) {
            abort(403, 'You do not have permission to edit this project.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:mine_tech,enviro,startup,other',
            'color_accent' => 'required|in:red,blue,green,yellow,purple',
            'is_draft' => 'boolean',
        ]);

        $project->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'category' => $validated['category'],
            'color_accent' => $validated['color_accent'],
            'is_draft' => $request->boolean('is_draft', false),
        ]);

        return redirect()->route('projects.index')
            ->with('success', 'Project updated successfully!');
    }

    /**
     * Remove the project (soft delete/move to trash)
     */
    public function destroy(string $id)
    {
        $project = Project::findOrFail($id);
        
        // Only owner can delete
        if ($project->user_id !== Auth::id()) {
            abort(403, 'Only the project owner can delete it.');
        }

        $project->delete(); // Soft delete

        return redirect()->route('projects.index')
            ->with('success', 'Project moved to trash.');
    }

    /**
     * Toggle star/favorite status
     */
    public function toggleStar(string $id)
    {
        $project = Project::findOrFail($id);
        
        // Only owner can star/unstar
        if ($project->user_id !== Auth::id()) {
            abort(403);
        }

        $project->is_starred = !$project->is_starred;
        $project->save();

        return redirect()->back()
            ->with('success', $project->is_starred ? 'Project starred!' : 'Project unstarred.');
    }

    /**
     * Restore project from trash
     */
    public function restore(string $id)
    {
        $project = Project::onlyTrashed()->findOrFail($id);
        
        // Only owner can restore
        if ($project->user_id !== Auth::id()) {
            abort(403);
        }

        $project->restore();

        return redirect()->back()
            ->with('success', 'Project restored successfully!');
    }

    /**
     * Permanently delete project
     */
    public function forceDelete(string $id)
    {
        $project = Project::onlyTrashed()->findOrFail($id);
        
        // Only owner can permanently delete
        if ($project->user_id !== Auth::id()) {
            abort(403);
        }

        $project->forceDelete();

        return redirect()->back()
            ->with('success', 'Project permanently deleted.');
    }

    /**
     * Show drafts
     */
    public function drafts()
    {
        $projects = Project::where('user_id', Auth::id())
            ->drafts()
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('projects.drafts', compact('projects'));
    }

    /**
     * Show trash
     */
    public function trash()
    {
        $projects = Project::where('user_id', Auth::id())
            ->onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->get();

        return view('projects.trash', compact('projects'));
    }

    /**
     * Show starred projects
     */
    public function starred()
    {
        $projects = Project::where('user_id', Auth::id())
            ->starred()
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('projects.starred', compact('projects'));
    }

    /**
     * Restore all projects from trash
     */
    public function restoreAll()
    {
        $restored = Project::where('user_id', Auth::id())
            ->onlyTrashed()
            ->get();

        foreach ($restored as $project) {
            $project->restore();
        }

        return redirect()->route('projects.trash')
            ->with('success', 'All projects have been restored!');
    }

    /**
     * Permanently delete all projects from trash
     */
    public function emptyTrash()
    {
        $count = Project::where('user_id', Auth::id())
            ->onlyTrashed()
            ->count();

        Project::where('user_id', Auth::id())
            ->onlyTrashed()
            ->forceDelete();

        return redirect()->route('projects.trash')
            ->with('success', "Trash emptied! {$count} project(s) permanently deleted.");
    }

    /**
     * Share project with a user by email or username
     */
    public function shareProject(Request $request, string $id)
    {
        $project = Project::withTrashed()->findOrFail($id);
        
        // Only owner can share
        if ($project->user_id !== Auth::id()) {
            return response()->json(['error' => 'Only the project owner can share this project.'], 403);
        }

        $request->validate([
            'identifier' => 'required|string',
            'can_edit' => 'boolean',
        ]);

        $identifier = trim($request->identifier);
        $canEdit = $request->boolean('can_edit', false);

        // Find user by email or username
        $user = \App\Models\User::where('email', $identifier)
            ->orWhere('name', $identifier)
            ->first();

        if (!$user) {
            return response()->json(['error' => 'User not found. Please check the email or username.'], 404);
        }

        // Can't share with yourself
        if ($user->id === Auth::id()) {
            return response()->json(['error' => 'You cannot share a project with yourself.'], 400);
        }

        // Check if already shared
        if ($project->sharedWith()->where('users.id', $user->id)->exists()) {
            return response()->json(['error' => 'This project is already shared with ' . $user->name . '.'], 400);
        }

        // Add share
        $project->sharedWith()->attach($user->id, ['can_edit' => $canEdit]);

        // Return updated shared users list
        $sharedUsers = $project->sharedWith()->get()->map(function($u) {
            return [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'can_edit' => $u->pivot->can_edit,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Project shared with ' . $user->name . ' successfully!',
            'sharedUsers' => $sharedUsers,
        ]);
    }

    /**
     * Remove a user from project shares
     */
    public function removeShare(string $id, string $userId)
    {
        $project = Project::withTrashed()->findOrFail($id);
        
        // Only owner can remove shares
        if ($project->user_id !== Auth::id()) {
            return response()->json(['error' => 'Only the project owner can manage sharing.'], 403);
        }

        $user = \App\Models\User::findOrFail($userId);
        $project->sharedWith()->detach($userId);

        return response()->json([
            'success' => true,
            'message' => 'Removed ' . $user->name . ' from shared users.',
        ]);
    }

    /**
     * Update share permission (toggle can_edit)
     */
    public function updateSharePermission(string $id, string $userId)
    {
        $project = Project::withTrashed()->findOrFail($id);
        
        // Only owner can update permissions
        if ($project->user_id !== Auth::id()) {
            return response()->json(['error' => 'Only the project owner can manage permissions.'], 403);
        }

        $share = $project->sharedWith()->where('users.id', $userId)->first();
        
        if (!$share) {
            return response()->json(['error' => 'User is not a collaborator on this project.'], 404);
        }

        // Toggle permission
        $newPermission = !$share->pivot->can_edit;
        $project->sharedWith()->updateExistingPivot($userId, ['can_edit' => $newPermission]);

        return response()->json([
            'success' => true,
            'can_edit' => $newPermission,
            'message' => 'Permission updated to ' . ($newPermission ? 'Can Edit' : 'View Only'),
        ]);
    }

    /**
     * Check if user can access this project
     */
    private function canAccess(Project $project): bool
    {
        $user = Auth::user();
        
        // Mining Tech team can access all projects
        if ($user->isMiningTech()) {
            return true;
        }
        
        // Owner can access
        if ($project->user_id === $user->id) {
            return true;
        }

        // Check if shared with user
        return $project->sharedWith()->where('users.id', $user->id)->exists();
    }

}
