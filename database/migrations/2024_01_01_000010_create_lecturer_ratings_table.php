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
        Schema::create('lecturer_ratings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dosen_id');
            $table->unsignedBigInteger('competition_id');
            $table->unsignedBigInteger('rated_by_user_id');
            $table->integer('activity_rating');
            $table->text('comments')->nullable();
            $table->timestamps();
            
            $table->foreign('dosen_id')->references('id')->on('users');
            $table->foreign('competition_id')->references('id')->on('competitions');
            $table->foreign('rated_by_user_id')->references('id')->on('users');
            
            $table->unique(['dosen_id', 'competition_id', 'rated_by_user_id'], 'lecturer_rating_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecturer_ratings');
    }
}; 