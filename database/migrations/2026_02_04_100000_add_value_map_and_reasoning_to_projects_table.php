<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Value Map fields (VPC right side - Value Proposition)
            $table->json('products_services')->nullable()->after('customer_gains');
            $table->json('pain_relievers')->nullable()->after('products_services');
            $table->json('gain_creators')->nullable()->after('pain_relievers');
            
            // Enhanced status management (draft -> progress -> complete)
            $table->enum('project_status', ['draft', 'progress', 'complete'])->default('draft')->after('is_draft');
            
            // ReAct Reasoning traces (JSON for audit trail)
            $table->json('reasoning_layer1')->nullable()->after('ai_reasoning'); // Empathy → Customer Profile
            $table->json('reasoning_layer2')->nullable()->after('reasoning_layer1'); // Customer Profile → Value Map
            
            // Approval tracking for Customer Profile
            $table->boolean('customer_profile_approved')->default(false)->after('customer_profile_generated');
            
            // Value Map generation tracking
            $table->boolean('value_map_generated')->default(false)->after('customer_profile_approved');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'products_services',
                'pain_relievers',
                'gain_creators',
                'project_status',
                'reasoning_layer1',
                'reasoning_layer2',
                'customer_profile_approved',
                'value_map_generated',
            ]);
        });
    }
};
