<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add role_id column to users table
        if (!Schema::hasColumn('users', 'role_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('role_id')->nullable()->after('id')->constrained()->onDelete('set null');
            });
        }

        // Migrate existing enum roles to the new roles table
        $roleMapping = [
            'user' => null,
            'mining_tech' => null,
            'super_admin' => null,
        ];

        // Get role IDs
        $roles = DB::table('roles')->whereIn('name', ['user', 'mining_tech', 'super_admin'])->get();
        foreach ($roles as $role) {
            $roleMapping[$role->name] = $role->id;
        }

        // Update users with their corresponding role_id
        foreach ($roleMapping as $roleName => $roleId) {
            if ($roleId) {
                DB::table('users')->where('role', $roleName)->update(['role_id' => $roleId]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
    }
};
