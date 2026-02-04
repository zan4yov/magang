<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    // Role constants (kept for backward compatibility)
    const ROLE_USER = 'user';
    const ROLE_MINING_TECH = 'mining_tech';
    const ROLE_SUPER_ADMIN = 'super_admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the role that this user belongs to.
     */
    public function roleRelation()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Check if user is a regular user
     */
    public function isUser(): bool
    {
        return $this->role === self::ROLE_USER;
    }

    /**
     * Check if user is in Mining Technology Team
     */
    public function isMiningTech(): bool
    {
        return $this->role === self::ROLE_MINING_TECH;
    }

    /**
     * Check if user is a Super Admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasRole(...$roles): bool
    {
        return in_array($this->role, $roles);
    }

    /**
     * Check if user has a specific permission (via their role).
     *
     * @param string $permissionName
     * @return bool
     */
    public function hasPermission(string $permissionName): bool
    {
        if ($this->roleRelation) {
            return $this->roleRelation->hasPermission($permissionName);
        }
        
        // Fallback: super_admin has all permissions
        if ($this->isSuperAdmin()) {
            return true;
        }
        
        return false;
    }

    /**
     * Check if this user is currently online (has active session in last 5 minutes).
     *
     * @return bool
     */
    public function isOnline(): bool
    {
        return DB::table('sessions')
            ->where('user_id', $this->id)
            ->where('last_activity', '>', time() - 300) // 5 minutes
            ->exists();
    }

    /**
     * Get the user's last activity timestamp.
     *
     * @return int|null
     */
    public function getLastActivityAttribute(): ?int
    {
        $session = DB::table('sessions')
            ->where('user_id', $this->id)
            ->orderBy('last_activity', 'desc')
            ->first();
        
        return $session ? $session->last_activity : null;
    }

    /**
     * Relationship: User has many projects
     */
    public function projects()
    {
        return $this->hasMany(\App\Models\Project::class);
    }

    /**
     * Relationship: Projects shared with this user
     */
    public function sharedProjects()
    {
        return $this->belongsToMany(\App\Models\Project::class, 'project_shares', 'shared_with_user_id', 'project_id')
            ->withPivot('can_edit')
            ->withTimestamps();
    }

    /**
     * Get the count of currently online users.
     *
     * @param int $minutes Minutes threshold for considering a user online (default: 5)
     * @return int
     */
    public static function getOnlineCount(int $minutes = 5): int
    {
        return DB::table('sessions')
            ->whereNotNull('user_id')
            ->where('last_activity', '>', time() - ($minutes * 60))
            ->distinct('user_id')
            ->count('user_id');
    }

    /**
     * Get currently online users.
     *
     * @param int $minutes Minutes threshold for considering a user online (default: 5)
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getOnlineUsers(int $minutes = 5): \Illuminate\Database\Eloquent\Collection
    {
        $onlineUserIds = DB::table('sessions')
            ->whereNotNull('user_id')
            ->where('last_activity', '>', time() - ($minutes * 60))
            ->pluck('user_id')
            ->unique();

        return self::whereIn('id', $onlineUserIds)->get();
    }
}

