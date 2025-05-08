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
        Schema::create('lecturer_mentorships', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dosen_id'); // Lecturer who is mentoring
            $table->unsignedBigInteger('competition_id'); // Competition being mentored
            $table->timestamps();
            
            $table->foreign('dosen_id')->references('id')->on('users');
            $table->foreign('competition_id')->references('id')->on('competitions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecturer_mentorships');
    }
}; 