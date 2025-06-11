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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role', 20)->default('user'); // admin, dosen, mahasiswa
            $table->unsignedBigInteger('level_id')->nullable(); // User level or permission level
            $table->string('photo')->nullable();
            $table->string('nim', 20)->nullable(); // Student ID number (for students)
            $table->string('nip', 20)->nullable();
            $table->unsignedBigInteger('program_studi_id')->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->timestamps();
            
            $table->foreign('program_studi_id')->references('id')->on('study_programs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
}; 