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
        Schema::create('sub_competition_participants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sub_competition_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('mentor_id')->nullable();
            $table->string('team_name')->nullable();
            $table->string('status', 20)->default('registered');
            $table->string('status_mentor', 20)->default('pending'); // Status for mentor approval
            $table->timestamps();
            
            $table->foreign('sub_competition_id')->references('id')->on('sub_competitions')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('mentor_id')->references('id')->on('users')->onDelete('set null');
            
            // Ensure a user can only participate once in a specific sub-competition
            $table->unique(['sub_competition_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_competition_participants');
    }
}; 