<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'category',
        'color_accent',
        'is_starred',
        'is_draft',
        'last_viewed_at',
        // Empathy Map fields
        'empathy_says',
        'empathy_thinks',
        'empathy_does',
        'empathy_feels',
        // Customer Profile fields
        'customer_jobs',
        'customer_pains',
        'customer_gains',
        'ai_reasoning',
        // Value Map fields (VPC right side)
        'products_services',
        'pain_relievers',
        'gain_creators',
        // Wizard tracking
        'empathy_map_completed',
        'customer_profile_generated',
        'customer_profile_approved',
        'value_map_generated',
        // Project status (draft, progress, complete)
        'project_status',
        // ReAct reasoning traces
        'reasoning_layer1',
        'reasoning_layer2',
    ];

    protected $casts = [
        'is_starred' => 'boolean',
        'is_draft' => 'boolean',
        'last_viewed_at' => 'datetime',
        'empathy_says' => 'array',
        'empathy_thinks' => 'array',
        'empathy_does' => 'array',
        'empathy_feels' => 'array',
        'customer_jobs' => 'array',
        'customer_pains' => 'array',
        'customer_gains' => 'array',
        'products_services' => 'array',
        'pain_relievers' => 'array',
        'gain_creators' => 'array',
        'reasoning_layer1' => 'array',
        'reasoning_layer2' => 'array',
        'empathy_map_completed' => 'boolean',
        'customer_profile_generated' => 'boolean',
        'customer_profile_approved' => 'boolean',
        'value_map_generated' => 'boolean',
    ];

    /**
     * Relationship: Project belongs to a User (owner)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Users this project is shared with
     */
    public function sharedWith(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_shares', 'project_id', 'shared_with_user_id')
            ->withPivot('can_edit')
            ->withTimestamps();
    }

    /**
     * Scope: Get starred projects
     */
    public function scopeStarred($query)
    {
        return $query->where('is_starred', true);
    }

    /**
     * Scope: Get draft projects
     */
    public function scopeDrafts($query)
    {
        return $query->where('is_draft', true);
    }

    /**
     * Scope: Get recently viewed projects
     */
    public function scopeRecentlyViewed($query)
    {
        return $query->whereNotNull('last_viewed_at')
            ->orderBy('last_viewed_at', 'desc');
    }

    /**
     * Scope: Get personal projects (owned by user)
     */
    public function scopePersonal($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: Get shared projects (shared with user)
     */
    public function scopeShared($query, $userId)
    {
        return $query->whereHas('sharedWith', function($q) use ($userId) {
            $q->where('users.id', $userId);
        });
    }

    /**
     * Check if user can edit this project
     */
    public function canEdit(User $user): bool
    {
        // Owner can always edit
        if ($this->user_id === $user->id) {
            return true;
        }

        // Check if shared with edit permission
        return $this->sharedWith()
            ->where('users.id', $user->id)
            ->wherePivot('can_edit', true)
            ->exists();
    }

    /**
     * Update last viewed timestamp
     */
    public function markAsViewed(): void
    {
        $this->update(['last_viewed_at' => now()]);
    }

    /**
     * Check if project has empathy map data
     */
    public function hasEmpathyMap(): bool
    {
        return $this->empathy_map_completed && 
               !empty($this->empathy_says) && 
               !empty($this->empathy_thinks) && 
               !empty($this->empathy_does) && 
               !empty($this->empathy_feels);
    }

    /**
     * Check if customer profile has been generated
     */
    public function hasCustomerProfile(): bool
    {
        return $this->customer_profile_generated && 
               !empty($this->customer_jobs) && 
               !empty($this->customer_pains) && 
               !empty($this->customer_gains);
    }

    /**
     * Get empathy map data as array
     */
    public function getEmpathyMapData(): array
    {
        return [
            'says' => $this->empathy_says ?? [],
            'thinks' => $this->empathy_thinks ?? [],
            'does' => $this->empathy_does ?? [],
            'feels' => $this->empathy_feels ?? [],
        ];
    }

    /**
     * Get customer profile data as array
     */
    public function getCustomerProfileData(): array
    {
        return [
            'customer_jobs' => $this->customer_jobs ?? [],
            'customer_pains' => $this->customer_pains ?? [],
            'customer_gains' => $this->customer_gains ?? [],
            'reasoning' => $this->ai_reasoning ?? '',
            'reasoning_trace' => $this->reasoning_layer1 ?? [],
        ];
    }

    /**
     * Check if project has Value Map data
     */
    public function hasValueMap(): bool
    {
        return $this->value_map_generated && 
               !empty($this->products_services) && 
               !empty($this->pain_relievers) && 
               !empty($this->gain_creators);
    }

    /**
     * Get Value Map data as array
     */
    public function getValueMapData(): array
    {
        return [
            'products_services' => $this->products_services ?? [],
            'pain_relievers' => $this->pain_relievers ?? [],
            'gain_creators' => $this->gain_creators ?? [],
            'reasoning_trace' => $this->reasoning_layer2 ?? [],
        ];
    }

    /**
     * Check if Customer Profile is approved for Value Map generation
     */
    public function canGenerateValueMap(): bool
    {
        return $this->customer_profile_approved && 
               $this->hasCustomerProfile();
    }

    /**
     * Get current project status with details
     */
    public function getStatusDetails(): array
    {
        $status = $this->project_status ?? 'draft';
        
        $statusMap = [
            'draft' => [
                'label' => 'Draft',
                'color' => '#f59e0b',
                'icon' => 'pencil',
                'description' => 'Project is being created',
            ],
            'progress' => [
                'label' => 'In Progress',
                'color' => '#3b82f6',
                'icon' => 'clock',
                'description' => 'Customer Profile approved, awaiting Value Map',
            ],
            'complete' => [
                'label' => 'Complete',
                'color' => '#10b981',
                'icon' => 'check',
                'description' => 'Value Map generated',
            ],
        ];

        return $statusMap[$status] ?? $statusMap['draft'];
    }

    /**
     * Update project status based on current state
     */
    public function updateProjectStatus(): void
    {
        if ($this->hasValueMap()) {
            $this->update(['project_status' => 'complete', 'is_draft' => false]);
        } elseif ($this->customer_profile_approved && $this->hasCustomerProfile()) {
            $this->update(['project_status' => 'progress', 'is_draft' => true]);
        } else {
            $this->update(['project_status' => 'draft', 'is_draft' => true]);
        }
    }

    /**
     * Scope: Get projects by status
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('project_status', $status);
    }
}
