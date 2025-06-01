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
        Schema::create('fcm_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('token', 512); // Changed from text to string with specific length
            $table->string('user_id')->nullable(); // Can be null for anonymous users
            $table->string('platform')->default('android');
            $table->timestamp('timestamp');
            $table->string('app_version')->nullable();
            $table->string('package_name')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Add unique constraint on token
            $table->unique('token');
            
            // Index for better performance
            $table->index(['user_id', 'is_active']);
            $table->index(['platform', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fcm_tokens');
    }
};
