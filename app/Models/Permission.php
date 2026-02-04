<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    // Common permission constants
    const MANAGE_USERS = 'manage_users';
    const MANAGE_ROLES = 'manage_roles';
    const DELETE_USERS = 'delete_users';
    const RESET_PASSWORDS = 'reset_passwords';
    const VIEW_ALL_PROJECTS = 'view_all_projects';
    const MANAGE_PROJECTS = 'manage_projects';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get the roles that have this permission.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permission_role');
    }
}
