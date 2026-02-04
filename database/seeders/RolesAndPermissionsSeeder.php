<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Permissions
        $permissions = [
            Permission::MANAGE_USERS => Permission::firstOrCreate(['name' => Permission::MANAGE_USERS]),
            Permission::MANAGE_ROLES => Permission::firstOrCreate(['name' => Permission::MANAGE_ROLES]),
            Permission::DELETE_USERS => Permission::firstOrCreate(['name' => Permission::DELETE_USERS]),
            Permission::RESET_PASSWORDS => Permission::firstOrCreate(['name' => Permission::RESET_PASSWORDS]),
            Permission::VIEW_ALL_PROJECTS => Permission::firstOrCreate(['name' => Permission::VIEW_ALL_PROJECTS]),
            Permission::MANAGE_PROJECTS => Permission::firstOrCreate(['name' => Permission::MANAGE_PROJECTS]),
        ];

        // Create Roles
        $superAdmin = Role::firstOrCreate(
            ['name' => Role::SUPER_ADMIN],
            ['description' => 'Full system access with all permissions']
        );

        $miningTech = Role::firstOrCreate(
            ['name' => Role::MINING_TECH],
            ['description' => 'Mining Technology Team with project viewing access']
        );

        $user = Role::firstOrCreate(
            ['name' => Role::USER],
            ['description' => 'Regular user with basic access']
        );

        // Assign all permissions to super_admin
        $superAdmin->permissions()->sync(array_map(fn($p) => $p->id, $permissions));

        // Assign view_all_projects to mining_tech
        $miningTech->permissions()->sync([
            $permissions[Permission::VIEW_ALL_PROJECTS]->id,
        ]);

        // Regular users have no special permissions (they can only access their own projects)
        $user->permissions()->sync([]);

        $this->command->info('Roles and permissions seeded successfully!');
        $this->command->info("Super Admin has " . $superAdmin->permissions->count() . " permissions");
        $this->command->info("Mining Tech has " . $miningTech->permissions->count() . " permissions");
        $this->command->info("User has " . $user->permissions->count() . " permissions");
    }
}
