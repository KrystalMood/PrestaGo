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
        Schema::create('competition_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('competition_id')->constrained()->onDelete('cascade');
            $table->integer('overall_rating');
            $table->integer('organization_rating');
            $table->integer('judging_rating');
            $table->integer('learning_rating');
            $table->integer('materials_rating');
            $table->text('strengths');
            $table->text('improvements');
            $table->text('skills_gained');
            $table->enum('recommendation', ['yes', 'maybe', 'no']);
            $table->text('additional_comments')->nullable();
            $table->timestamps();
            
            // Ensure a user can only provide one feedback per competition
            $table->unique(['user_id', 'competition_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competition_feedback');
    }
}; 