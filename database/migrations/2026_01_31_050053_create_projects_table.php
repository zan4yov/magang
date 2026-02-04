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
        if (!Schema::hasTable('projects')) {
            Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('category', ['mine_tech', 'enviro', 'startup', 'other'])->default('other');
            $table->enum('color_accent', ['red', 'blue', 'green', 'yellow', 'purple'])->default('blue');
            $table->boolean('is_starred')->default(false);
            $table->boolean('is_draft')->default(false);
            $table->timestamp('last_viewed_at')->nullable();
            $table->softDeletes(); // for trash functionality
            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('is_starred');
            $table->index('deleted_at');
            $table->index('last_viewed_at');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
