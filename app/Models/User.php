<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    // Role constants
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
}
