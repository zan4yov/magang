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
            // Empathy Map fields - what users input
            $table->json('empathy_says')->nullable()->after('description');
            $table->json('empathy_thinks')->nullable()->after('empathy_says');
            $table->json('empathy_does')->nullable()->after('empathy_thinks');
            $table->json('empathy_feels')->nullable()->after('empathy_does');
            
            // Customer Profile fields - AI generated output
            $table->json('customer_jobs')->nullable()->after('empathy_feels');
            $table->json('customer_pains')->nullable()->after('customer_jobs');
            $table->json('customer_gains')->nullable()->after('customer_pains');
            
            // AI reasoning audit trail
            $table->text('ai_reasoning')->nullable()->after('customer_gains');
            
            // Wizard completion tracking
            $table->boolean('empathy_map_completed')->default(false)->after('ai_reasoning');
            $table->boolean('customer_profile_generated')->default(false)->after('empathy_map_completed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'empathy_says',
                'empathy_thinks',
                'empathy_does',
                'empathy_feels',
                'customer_jobs',
                'customer_pains',
                'customer_gains',
                'ai_reasoning',
                'empathy_map_completed',
                'customer_profile_generated',
            ]);
        });
    }
};
